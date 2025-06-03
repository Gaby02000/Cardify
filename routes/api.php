<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GiftCardApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;

// Ruta pública (sin auth) para listar giftcards
Route::apiResource('giftcards', GiftCardApiController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryApiController::class)->only(['index', 'show']);
// Las demás siguen protegidas
Route::middleware('auth:sanctum')->group(function () {
});