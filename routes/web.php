<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// --- Публічні сторінки користувача ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::view('/checkout', 'pages.checkout')->name('checkout');

// --- Кошик і оформлення ---
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

// --- SPA-адмінка ---
Route::view('/admin/{any?}', 'layouts.admin_spa')->where('any', '.*');
