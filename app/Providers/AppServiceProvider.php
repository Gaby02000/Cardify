<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use MercadoPago\MercadoPagoConfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        # Log::info('app.env:', ['body' => $uploadResponse->body()]);
        if(config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        $this->ensureCaBundle();

        // En local, el SDK de Mercado Pago omite la verificación de certificado
        // (evita "unable to get local issuer certificate" en Windows/dev).
        // En cualquier otro entorno queda con verificación TLS completa.
        if (app()->environment('local')) {
            MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        }
    }

    /**
     * En Windows/local muchas instalaciones de PHP no tienen configurado el
     * bundle de certificados raíz, y las llamadas HTTPS salientes (Mercado Pago,
     * Cloudinary, etc.) fallan con "unable to get local issuer certificate".
     * Si cURL/OpenSSL no tienen CA configurado, usamos el bundle incluido en el repo.
     */
    private function ensureCaBundle(): void
    {
        // En hosts reales confiamos en los certificados del sistema.
        if (!app()->environment('local')) {
            return;
        }

        if (ini_get('curl.cainfo') || ini_get('openssl.cafile') || getenv('CURL_CA_BUNDLE')) {
            return;
        }

        $bundle = storage_path('certs/cacert.pem');

        if (is_file($bundle)) {
            putenv('CURL_CA_BUNDLE=' . $bundle);
            putenv('SSL_CERT_FILE=' . $bundle);
            $_SERVER['CURL_CA_BUNDLE'] = $bundle;
        }
    }
}
