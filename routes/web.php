<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// --- Публічні сторінки користувача ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');

// --- Інші Blade-сторінки (поки заглушки) ---
Route::view('/product/{slug}', 'pages.product')->name('product');
Route::view('/cart', 'pages.cart')->name('cart');

Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product');

// --- SPA-адмінка ---
Route::view('/admin/{any?}', 'layouts.admin_spa')->where('any', '.*');
