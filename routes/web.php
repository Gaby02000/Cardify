<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/home', function () {
//     return view('home');
// })->middleware('auth');

Route::get('/', [GiftCardController::class, 'index'])->name('home');
Route::get('/giftcards/{id}', [GiftCardController::class, 'show'])->name('giftcards.show');

// Para carrito y ordenes después
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

