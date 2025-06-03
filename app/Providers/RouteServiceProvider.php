<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            // Quitá el prefijo 'api' para api.php
            Route::middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
