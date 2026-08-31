<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /** La public key para que el frontend pueda suscribirse. */
    public function publicKey(): JsonResponse
    {
        return response()->json(['key' => config('webpush.vapid.public_key')]);
    }

    /** Alta / actualización de una suscripción del navegador. */
    public function subscribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint' => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
            'contentEncoding' => ['nullable', 'string'],
        ]);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'user_client_id' => $request->user('sanctum')?->id,
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
                'content_encoding' => $data['contentEncoding'] ?? 'aes128gcm',
            ]
        );

        return response()->json(['message' => 'Suscripción registrada', 'id' => $subscription->id], 201);
    }

    /** Baja de una suscripción por su endpoint. */
    public function unsubscribe(Request $request): JsonResponse
    {
        $data = $request->validate(['endpoint' => ['required', 'string']]);

        PushSubscription::where('endpoint', $data['endpoint'])->delete();

        return response()->json(['message' => 'Suscripción eliminada']);
    }
}
