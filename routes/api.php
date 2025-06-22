<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GiftCardApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CartItemApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\Api\UserClientAuthController;
use App\Models\User;
use Illuminate\Session\Middleware\StartSession;

Route::get('/giftcards', [GiftCardApiController::class, 'index']);
Route::get('/categories', [CategoryApiController::class, 'index']);

Route::middleware(['api', StartSession::class])->group(function () {
    Route::post('/login', [LoginApiController::class, 'login']);
    Route::post('/logout', [LoginApiController::class, 'logout']);
    Route::get('/user', [LoginApiController::class, 'user']);    
    Route::post('/register', [UserClientAuthController::class, 'register']);
    Route::get('/cart', [CartApiController::class, 'show']);
    Route::post('/cart/add-item', [CartApiController::class, 'addItem']);
    Route::post('/cart/clear', [CartApiController::class, 'clear']);
    Route::put('/cart-item/{cartItem}', [CartItemApiController::class, 'update']);
    Route::delete('/cart-item/{cartItem}', [CartItemApiController::class, 'destroy']);

    Route::post('/payment', [MercadoPagoHookController::class, 'handle']);

    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
    });


// Route::middleware(['api', StartSession::class, 'auth:sanctum'])->group(function () {
//     Route::post('/orders', [OrderApiController::class, 'store']);
//     Route::get('/orders/{order}', [OrderApiController::class, 'show']);
// });
