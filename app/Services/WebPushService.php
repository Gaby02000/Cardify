<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    /**
     * Envía un payload (title/body/url/...) a un conjunto de suscripciones.
     * Si no se pasan, va a todas. Las suscripciones caídas (404/410) se borran.
     *
     * @param  array<string,mixed>  $payload
     * @param  iterable<PushSubscription>|null  $subscriptions
     * @return array{queued:int,ok:int,gone:int,failed:int}
     */
    public function send(array $payload, ?iterable $subscriptions = null): array
    {
        $vapid = config('webpush.vapid');

        if (empty($vapid['public_key']) || empty($vapid['private_key'])) {
            throw new \RuntimeException(
                'Faltan las claves VAPID. Generalas con "php artisan webpush:vapid" y cargalas en el .env.'
            );
        }

        // En local (Windows) curl no encuentra el bundle de CA; usamos el mismo
        // que ya se usa para Mercado Pago. En Vercel curl ya tiene sus CAs.
        $caBundle = storage_path('certs/cacert.pem');
        $clientOptions = is_file($caBundle) ? ['verify' => $caBundle] : [];

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => $vapid['subject'],
                'publicKey' => $vapid['public_key'],
                'privateKey' => $vapid['private_key'],
            ],
        ], [], 30, $clientOptions);
        $webPush->setReuseVAPIDHeaders(true);

        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $subscriptions ??= PushSubscription::query()->cursor();

        $queued = 0;
        foreach ($subscriptions as $sub) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                    'contentEncoding' => $sub->content_encoding ?: 'aes128gcm',
                ]),
                $body
            );
            $queued++;
        }

        $ok = 0;
        $gone = 0;
        $failed = 0;

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $ok++;
                continue;
            }

            $status = $report->getResponse()?->getStatusCode();
            if ($report->isSubscriptionExpired() || in_array($status, [404, 410], true)) {
                PushSubscription::where('endpoint', $report->getEndpoint())->delete();
                $gone++;
                continue;
            }

            $failed++;
            Log::warning('WebPush: envío fallido', [
                'endpoint' => $report->getEndpoint(),
                'reason' => $report->getReason(),
            ]);
        }

        return compact('queued', 'ok', 'gone', 'failed');
    }
}
