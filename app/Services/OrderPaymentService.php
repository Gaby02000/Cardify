<?php

namespace App\Services;

use App\Mail\GiftCardCodes;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class OrderPaymentService
{
    private const STATUS_MAP = [
        'approved'     => 'pagado',
        'authorized'   => 'pagado',
        'rejected'     => 'rechazado',
        'cancelled'    => 'rechazado',
        'refunded'     => 'reembolsado',
        'charged_back' => 'reembolsado',
        'in_process'   => 'pendiente',
        'pending'      => 'pendiente',
    ];

    /**
     * Consulta el pago en Mercado Pago y sincroniza la orden asociada.
     * Devuelve la orden actualizada, o null si no se pudo resolver.
     */
    public function syncFromPayment(int|string $paymentId): ?Order
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        try {
            $payment = (new PaymentClient())->get((int) $paymentId);
        } catch (\Throwable $e) {
            Log::warning("MP: no se pudo consultar el pago {$paymentId}: " . $e->getMessage());
            return null;
        }

        $order = Order::with('orderItems.giftCard')->find($payment->external_reference);
        if (!$order) {
            Log::warning('MP: pago sin orden asociada', ['external_reference' => $payment->external_reference]);
            return null;
        }

        $this->applyStatus($order, (string) $payment->status);

        return $order->fresh(['orderItems.giftCard']);
    }

    public function applyStatus(Order $order, string $mpStatus): void
    {
        $target = self::STATUS_MAP[$mpStatus] ?? $order->status;

        if ($target === 'pagado') {
            $wasPaid = $order->status === 'pagado';
            $this->fulfill($order);

            // Solo en la transición real pendiente -> pagado, y ya con la
            // transacción de fulfill() confirmada.
            if (! $wasPaid && $order->fresh()?->status === 'pagado') {
                $this->notifyPurchase($order);
            }
        } elseif ($target === 'rechazado') {
            $this->reject($order);
        }
        // 'pendiente' / desconocido -> no tocamos la orden
    }

    /**
     * Marca la orden como pagada: descuenta stock, genera los códigos, manda
     * el mail y vacía el carrito. Idempotente y protegido con lockForUpdate:
     * si ya está pagada devuelve los códigos existentes sin re-ejecutar nada.
     *
     * @return array<int, array{gift_card: string, code: string}>
     */
    public function fulfill(Order $order): array
    {
        return DB::transaction(function () use ($order) {
            /** @var Order $locked */
            $locked = Order::with('orderItems.giftCard')
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($locked->status === 'pagado') {
                return $locked->codes ?? [];
            }

            foreach ($locked->orderItems as $item) {
                $gc = $item->giftCard;
                if ($gc) {
                    $gc->stock = max(0, $gc->stock - $item->quantity);
                    $gc->save();
                }
            }

            $codes = [];
            foreach ($locked->orderItems as $item) {
                for ($i = 0; $i < $item->quantity; $i++) {
                    $codes[] = [
                        'gift_card' => $item->giftCard->title ?? 'Gift Card',
                        'code' => strtoupper(uniqid('GC-')),
                    ];
                }
            }

            $locked->update(['status' => 'pagado', 'codes' => $codes]);

            if ($locked->cart_id) {
                CartItem::where('cart_id', $locked->cart_id)->delete();
            }

            try {
                if ($locked->user) {
                    Mail::to($locked->user->email)->send(new GiftCardCodes($locked->user, $codes));
                }
            } catch (\Throwable $e) {
                Log::warning('MP: no se pudo enviar el email de códigos: ' . $e->getMessage());
            }

            Log::info("Orden {$locked->id} -> pagado", ['codes' => count($codes)]);

            return $codes;
        });
    }

    public function reject(Order $order): void
    {
        if (in_array($order->status, ['rechazado', 'pagado', 'reembolsado'], true)) {
            return;
        }
        $order->update(['status' => 'rechazado']);
        Log::info("Orden {$order->id} -> rechazado");
    }

    /**
     * Notificación push "compra confirmada" a los dispositivos del comprador.
     * Best-effort: cualquier error se loguea y no corta el flujo del pago.
     */
    private function notifyPurchase(Order $order): void
    {
        if (! $order->user_client_id) {
            return;
        }

        $subscriptions = PushSubscription::where('user_client_id', $order->user_client_id)->get();
        if ($subscriptions->isEmpty()) {
            // Sin dispositivos suscriptos: no marcamos nada, así si el usuario
            // se suscribe más tarde y la orden se re-confirma, se puede enviar.
            return;
        }

        // Dedupe: el webhook de MP y el retorno del frontend pueden confirmar
        // la misma orden casi a la vez. Cache::add es atómico.
        $dedupeKey = 'push-order-notified:' . $order->id;
        if (! Cache::add($dedupeKey, true, now()->addDay())) {
            return;
        }

        try {
            app(WebPushService::class)->send([
                'title' => '¡Compra confirmada! 🎉',
                'body' => sprintf(
                    'Tu pago de $%s se aprobó. Entrá para ver tus códigos.',
                    number_format((float) $order->total_price, 2, ',', '.')
                ),
                'url' => '/order-confirmed?external_reference=' . $order->id,
                'tag' => 'cardify-order-' . $order->id,
            ], $subscriptions);
        } catch (\Throwable $e) {
            Cache::forget($dedupeKey); // permitir reintento
            Log::warning('Push de compra no enviado: ' . $e->getMessage());
        }
    }
}
