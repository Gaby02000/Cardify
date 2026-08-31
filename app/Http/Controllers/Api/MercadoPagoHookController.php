<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoHookController extends Controller
{
    /**
     * Webhook de Mercado Pago. Verifica el pago y sincroniza la orden
     * (entrega de códigos incluida). Siempre responde 200 rápido: MP
     * reintenta ante cualquier otro código.
     */
    public function handle(Request $request, OrderPaymentService $payments)
    {
        Log::info('Webhook Mercado Pago recibido', $request->all());

        $type = $request->input('type', $request->input('topic'));
        $paymentId = $request->input('data.id', $request->input('data_id', $request->query('id')));

        if ($type !== 'payment' || !$paymentId) {
            return response()->json(['ignored' => true], 200);
        }

        $payments->syncFromPayment($paymentId);

        return response()->json(['ok' => true], 200);
    }
}
