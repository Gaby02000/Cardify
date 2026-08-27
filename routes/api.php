<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GiftCardApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CartItemApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\Api\UserClientAuthController;
use App\Http\Controllers\Api\MercadoPagoHookController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/giftcards', [GiftCardApiController::class, 'index']);
Route::get('/categories', [CategoryApiController::class, 'index']);

Route::post('/login', [LoginApiController::class, 'login']);
Route::post('/register', [UserClientAuthController::class, 'register']);

// Webhook de Mercado Pago (lo invoca MP, sin usuario)
Route::post('/payment', [MercadoPagoHookController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| Carrito
|--------------------------------------------------------------------------
| Accesible con o sin sesión. Si viene el header Authorization: Bearer,
| el controlador resuelve el usuario con el guard 'sanctum'; si no, usa
| el `session_id` (UUID) que envía el frontend para el carrito de invitado.
*/
Route::get('/cart', [CartApiController::class, 'show']);
Route::post('/cart/add-item', [CartApiController::class, 'addItem']);
Route::post('/cart/clear', [CartApiController::class, 'clear']);
Route::put('/cart-item/{cartItem}', [CartItemApiController::class, 'update']);
Route::delete('/cart-item/{cartItem}', [CartItemApiController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Rutas que requieren usuario autenticado (token de Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginApiController::class, 'user']);
    Route::post('/logout', [LoginApiController::class, 'logout']);

    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
});
