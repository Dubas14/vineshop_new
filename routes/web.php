<?php



use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\URL;

if (env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}


// --- Публічні сторінки користувача ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/category/{id}', [CatalogController::class, 'byCategory'])->name('catalog.byCategory');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product');

// --- Кошик (через SPA) ---

Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::view('/cart', 'pages.cart')->name('cart');

// --- Оформлення замовлення (залишається Blade або SPA на вибір) ---
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

// --- Кабінет користувача ---
Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- SPA-адмінка ---
Route::view('/admin/{any?}', 'layouts.admin_spa')->where('any', '.*');

require __DIR__.'/auth.php';
