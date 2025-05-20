<?php
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use Mockery\Generator\StringManipulation\Pass\Pass;

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/home', function () {
//     return view('home');
// })->middleware('auth');

//Route::get('/', [GiftCardController::class, 'index'])->name('home')->middleware('auth');
//Route::get('/giftcards/{id}', [GiftCardController::class, 'show'])->name('giftcards.show')->middleware('auth');
//Route::get('/giftcards/create', [GiftCardController::class, 'create'])->name('giftcards.create');
//Route::post('/giftcards', [GiftCardController::class, 'store'])->name('giftcards.store');

Route::get('/', [GiftCardController::class, 'index'])->name('home')->middleware('auth');
// Para giftcards
Route::resource('/giftcards', GiftCardController::class)->middleware('auth');
// Para categorias
Route::resource('/categories', CategoryController::class)->middleware('auth');

Route::resource('/users', UserProfileController::class)
    ->only(['edit', 'update', 'show'])
    ->middleware('auth');

// Para carrito y ordenes después
Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::resource('/orders', OrderController::class)->middleware('auth');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

Route::get('password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::get('/forgot-password', function () {return view('forgot_password');})->name('password.request');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::resource('dashboard', DashboardController::class)->only(['index'])->middleware('auth');


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');


