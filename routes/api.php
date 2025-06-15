<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GiftCardApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CartItemApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\LoginApiController;

Route::post('/login', [LoginApiController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [LoginApiController::class, 'logout']);

// Ruta pública (sin auth) para listar giftcards
Route::get('/giftcards', [GiftCardApiController::class, 'index']);

Route::get('/categories', [CategoryApiController::class, 'index']);

Route::get('/cart-items', [CartItemApiController::class, 'index']);
Route::post('/cart-items', [CartItemApiController::class, 'storeOrCreateCart']);
Route::put('/cart-items/{cartItem}', [CartItemApiController::class, 'update']);
Route::delete('/cart-items/{cartItem}', [CartItemApiController::class, 'destroy']);


Route::get('/cart', [CartApiController::class, 'show']);       // Ver carrito
Route::delete('/cart', [CartApiController::class, 'destroy']); // Vaciar carrito

// Las demás siguen protegidas
Route::middleware('auth:sanctum')->group(function () {
});