<?php

namespace App\Listeners;

use App\Models\KnownLogin;
use App\Models\User;
use App\Models\UserClient;
use App\Notifications\NewLoginNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendNewLoginAlert
{
    /**
     * Avisa por correo cuando se inicia sesión desde un dispositivo/IP que no
     * se había visto antes para esa cuenta. Cubre el panel (guard web) y la
     * tienda (guard user_client): ambos disparan el evento Login.
     */
    public function handle(Login $event): void
    {
        // Este listener cuelga del flujo de login: cualquier error suyo (tabla
        // sin migrar, DB caída, etc.) se loguea pero nunca rompe el acceso.
        try {
            $this->process($event);
        } catch (\Throwable $e) {
            Log::warning('Aviso de nuevo inicio de sesión: ' . $e->getMessage());
        }
    }

    private function process(Login $event): void
    {
        // Logins desde consola (tinker, seeders, tests) no interesan.
        if (app()->runningInConsole()) {
            return;
        }

        $notifiable = $event->user;
        if (! $notifiable instanceof User && ! $notifiable instanceof UserClient) {
            return;
        }

        $request = request();
        $ip = (string) ($request->ip() ?? '');
        $ua = (string) ($request->userAgent() ?? '');

        $fingerprint = hash('sha256', implode('|', [
            $notifiable::class,
            $notifiable->getKey(),
            $ip,
            $ua,
        ]));

        $known = KnownLogin::where('fingerprint', $fingerprint)->first();

        if ($known) {
            $known->forceFill(['last_seen_at' => now()])->saveQuietly();
            return;
        }

        KnownLogin::create([
            'notifiable_type' => $notifiable::class,
            'notifiable_id'   => $notifiable->getKey(),
            'fingerprint'     => $fingerprint,
            'ip'              => $ip !== '' ? $ip : null,
            'user_agent'      => $ua !== '' ? Str::limit($ua, 500, '') : null,
            'last_seen_at'    => now(),
        ]);

        // Cuenta recién creada: el primer login post-registro solo fija la
        // línea de base, no dispara aviso.
        if ($notifiable->created_at && $notifiable->created_at->gt(now()->subMinutes(2))) {
            return;
        }

        // Dispositivo/IP nuevos: se avisa después de responder, sin bloquear el
        // login ni depender de una cola (no hay worker en Vercel).
        $at = now();
        defer(function () use ($notifiable, $ip, $ua, $at) {
            try {
                $notifiable->notify(new NewLoginNotification($ip, $ua, $at));
            } catch (\Throwable $e) {
                Log::warning('No se pudo enviar el aviso de nuevo inicio de sesión: ' . $e->getMessage());
            }
        });
    }
}
