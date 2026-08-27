<?php

namespace App\Services;

use App\Mail\GiftCardCodes;
use App\Models\CartItem;
use App\Models\Order;
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
            $this->fulfill($order);
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
}
