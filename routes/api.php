<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GiftCardApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;

// Ruta pública (sin auth) para listar giftcards
Route::get('/giftcards', [GiftCardApiController::class, 'index']);
Route::get('/categories', [CategoryApiController::class, 'index']);

// Carrito
Route::get('/cart', [CartApiController::class, 'show']);
Route::post('/cart/items', [CartApiController::class, 'addItem']);
Route::delete('/cart/items/{item}', [CartApiController::class, 'removeItem']);
Route::delete('/cart', [CartApiController::class, 'clear']);

// Las demás siguen protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Ordenes
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
});