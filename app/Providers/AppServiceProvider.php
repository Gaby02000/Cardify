<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
    }
}
