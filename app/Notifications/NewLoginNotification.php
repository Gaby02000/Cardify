<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class NewLoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $ip,
        private readonly string $userAgent,
        private readonly Carbon $at,
    ) {
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = trim((string) ($notifiable->name ?? ''));
        $when = $this->at->clone()
            ->timezone('America/Argentina/Buenos_Aires')
            ->format('d/m/Y H:i');
        $location = $this->lookupLocation($this->ip);

        return (new MailMessage())
            ->subject('Nuevo inicio de sesión en Cardify')
            ->greeting($name !== '' ? "Hola, {$name}:" : 'Hola:')
            ->line('Detectamos un inicio de sesión en tu cuenta desde un dispositivo o una ubicación que no reconocemos.')
            ->line("**Fecha y hora:** {$when} (hora de Argentina)")
            ->line('**Dispositivo:** ' . $this->describeUserAgent($this->userAgent))
            ->line('**IP:** ' . ($this->ip !== '' ? $this->ip : 'desconocida') . ($location !== null ? " ({$location})" : ''))
            ->line('Si fuiste vos, podés ignorar este correo.')
            ->action('No fui yo: asegurar mi cuenta', $this->secureAccountUrl($notifiable))
            ->line('Si no reconocés este acceso, cambiá tu contraseña cuanto antes.');
    }

    /** Navegador y sistema operativo a partir del User-Agent, sin dependencias. */
    private function describeUserAgent(string $ua): string
    {
        if (trim($ua) === '') {
            return 'Desconocido';
        }

        $browser = match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/'), str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'Chrome/') => 'Chrome',
            str_contains($ua, 'Safari/') => 'Safari',
            default => 'Navegador desconocido',
        };

        $os = match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone'), str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Mac OS X'), str_contains($ua, 'Macintosh') => 'macOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'SO desconocido',
        };

        return "{$browser} en {$os}";
    }

    /**
     * Ubicación aproximada por IP. Best-effort: corre después de responder,
     * así que un par de segundos de latencia acá no afectan al login.
     */
    private function lookupLocation(string $ip): ?string
    {
        $publica = filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );

        if ($publica === false) {
            return null;
        }

        try {
            $res = Http::timeout(3)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,country,regionName,city',
            ]);

            if ($res->ok() && $res->json('status') === 'success') {
                $partes = array_filter([
                    $res->json('city'),
                    $res->json('regionName'),
                    $res->json('country'),
                ]);

                return $partes !== [] ? implode(', ', $partes) : null;
            }
        } catch (\Throwable) {
            // sin ubicación
        }

        return null;
    }

    private function secureAccountUrl(object $notifiable): string
    {
        // Cliente de la tienda -> su pantalla de cuenta en el frontend.
        if ($notifiable instanceof \App\Models\UserClient) {
            return rtrim((string) config('services.frontend_url'), '/') . '/mi-cuenta';
        }

        // Usuario del panel -> pedir un enlace de recuperación de contraseña.
        return route('password.request');
    }
}
