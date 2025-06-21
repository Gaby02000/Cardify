<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoHookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Nuevo webhook Mercado Pago recibido', $payload);

        $eventType = $request->input('type', $request->input('topic', ''));
        $resourceId = data_get($payload, 'data.id');

        if ($eventType === 'payment') {
            $logData = [
                'id_pago' => $resourceId,
                'evento' => $eventType,
                'datos' => $payload,
            ];

            Log::channel('database')->info('Evento de pago recibido desde Mercado Pago', $logData);
        }

        return response()->json(['success' => true], 200);
    }
}
