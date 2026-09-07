<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Pdf\OrderPdfController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Panel administrativo (guard 'web' → App\Models\User)
|--------------------------------------------------------------------------
*/

Route::get('/', [GiftCardController::class, 'index'])->name('home')->middleware('auth');

// Para giftcards
Route::middleware('auth')->group(function () {
    Route::post('/giftcards/{giftcard}/toggle', [GiftCardController::class, 'toggleActive'])->name('giftcards.toggle');
    Route::post('/giftcards/{giftcard}/duplicate', [GiftCardController::class, 'duplicate'])->name('giftcards.duplicate');
});
Route::resource('/giftcards', GiftCardController::class)->middleware('auth');

// Para categorias
Route::resource('/categories', CategoryController::class)->middleware('auth');

Route::resource('/users', UserProfileController::class)
    ->only(['edit', 'update', 'show'])
    ->middleware('auth');

// Para carrito y ordenes después
Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::resource('/orders', OrderController::class);

    // La factura expone datos del cliente y los ids son correlativos: tiene que
    // quedar siempre dentro del grupo autenticado.
    Route::get('/orders/{order}/pdf', [OrderPdfController::class, 'download'])->name('orders.pdf');
});

Route::resource('dashboard', DashboardController::class)->only(['index'])->middleware('auth');

// Promociones: envío de notificaciones push a los suscriptores
Route::middleware('auth')->group(function () {
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions', [PromotionController::class, 'send'])->name('promotions.send');
});

// Descuentos: aplicar/quitar promociones de precio sobre tarjetas o categorías
Route::middleware('auth')->group(function () {
    Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
    Route::post('/discounts/clear', [DiscountController::class, 'clear'])->name('discounts.clear');
});

/*
|--------------------------------------------------------------------------
| Autenticación del panel
|--------------------------------------------------------------------------
| Una sola definición por acción. Antes había pares duplicados (register y
| password.* estaban declarados dos veces con controladores distintos): ganaba
| siempre la última y las primeras quedaban muertas o directamente rotas.
*/

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Recuperación de contraseña. El link del mail se arma con route('password.reset'),
// así que estas URLs y ResetPasswordMail no se pueden desincronizar.
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
