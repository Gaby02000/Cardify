<?php

namespace App\Console\Commands;

use App\Services\WebPushService;
use Illuminate\Console\Command;

class SendPromoPush extends Command
{
    protected $signature = 'push:promo
                            {title : Título de la notificación}
                            {body : Texto de la notificación}
                            {--url=/ : A dónde lleva el click}
                            {--image= : URL de una imagen grande (opcional)}';

    protected $description = 'Envía una notificación push de promoción a todos los suscriptores';

    public function handle(WebPushService $push): int
    {
        $payload = array_filter([
            'title' => $this->argument('title'),
            'body' => $this->argument('body'),
            'url' => $this->option('url') ?: '/',
            'image' => $this->option('image') ?: null,
            'tag' => 'cardify-promo',
        ], fn ($v) => $v !== null);

        try {
            $result = $push->send($payload);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Enviadas: %d · Bajas: %d · Fallos: %d (encoladas: %d)',
            $result['ok'],
            $result['gone'],
            $result['failed'],
            $result['queued'],
        ));

        return self::SUCCESS;
    }
}
