<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoHookController extends Controller
{
    /**
     * Webhook de Mercado Pago. Sincroniza el estado de la orden según el pago.
     * Siempre responde 200 rápido: MP reintenta ante cualquier error.
     */
    public function handle(Request $request)
    {
        Log::info('Webhook Mercado Pago recibido', $request->all());

        $type = $request->input('type', $request->input('topic'));
        $paymentId = $request->input('data.id', $request->input('data_id', $request->query('id')));

        if ($type !== 'payment' || !$paymentId) {
            return response()->json(['ignored' => true], 200);
        }

        try {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
            $payment = (new PaymentClient())->get((int) $paymentId);
        } catch (\Throwable $e) {
            Log::warning('No se pudo consultar el pago en Mercado Pago: ' . $e->getMessage());
            return response()->json(['ok' => false], 200);
        }

        $order = Order::with('orderItems')->find($payment->external_reference);

        if (!$order) {
            Log::warning('Pago sin orden asociada', ['external_reference' => $payment->external_reference]);
            return response()->json(['ok' => true], 200);
        }

        $map = [
            'approved'   => 'pagado',
            'rejected'   => 'rechazado',
            'cancelled'  => 'rechazado',
            'refunded'   => 'reembolsado',
            'charged_back' => 'reembolsado',
            'in_process' => 'pendiente',
            'pending'    => 'pendiente',
        ];
        $newStatus = $map[$payment->status] ?? $order->status;

        if ($order->status === $newStatus) {
            return response()->json(['ok' => true], 200);
        }

        // Si el pago se cae y la orden estaba pendiente, devolvemos el stock reservado.
        if ($newStatus === 'rechazado' && $order->status === 'pendiente') {
            foreach ($order->orderItems as $oi) {
                GiftCard::where('id', $oi->gift_card_id)->increment('stock', $oi->quantity);
            }
        }

        $order->status = $newStatus;
        $order->save();

        Log::info("Orden {$order->id} -> {$newStatus} (pago {$payment->id}, estado MP: {$payment->status})");

        return response()->json(['ok' => true], 200);
    }
}
