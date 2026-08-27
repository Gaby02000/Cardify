<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:vapid';

    protected $description = 'Genera un par de claves VAPID para las notificaciones Web Push';

    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $this->newLine();
        $this->info('Pegá estas líneas en tu .env (y en las variables de entorno de Vercel):');
        $this->newLine();
        $this->line('VAPID_PUBLIC_KEY='.$keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY='.$keys['privateKey']);
        $this->line('VAPID_SUBJECT=mailto:tu-email@ejemplo.com');
        $this->newLine();
        $this->comment('En el frontend, VITE_VAPID_PUBLIC_KEY debe ser la misma public key.');

        return self::SUCCESS;
    }
}
