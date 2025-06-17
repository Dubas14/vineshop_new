<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\URL;


if (env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['uk', 'en'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 30));
})->name('lang.switch');

// --- Публічні сторінки ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/category/{id}', [CatalogController::class, 'byCategory'])->name('catalog.byCategory');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contacts', 'pages.contacts')->name('contacts');

// --- Кошик ---
Route::get('/debug-locale', function () {
    return app()->getLocale(); // має показати 'en' або 'uk'
});
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::view('/cart', 'pages.cart')->name('cart');

// --- Замовлення ---
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

// --- Кабінет користувача ---
Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard/orders', [App\Http\Controllers\OrderHistoryController::class, 'index'])->name('orders.index');
    Route::get('/dashboard/orders/{order}', [App\Http\Controllers\OrderHistoryController::class, 'show'])->name('orders.show');
    Route::get('/dashboard/favorites', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{product}', [App\Http\Controllers\FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product}', [App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- SPA-адмінка ---
Route::view('/admin/{any?}', 'layouts.admin_spa')->where('any', '.*');

// Підключення маршрутів автентифікації
require __DIR__.'/auth.php';
