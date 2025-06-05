<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\BannerController;
use App\Http\Controllers\CartController;

Route::post('/admin/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/admin/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/admin/user', [AuthController::class, 'user']);
Route::get('/banners', [BannerController::class, 'public']);

Route::middleware('web')->group(function () {
    Route::get('/cart', [CartController::class, 'apiIndex']);
    Route::put('/cart/update/{id}', [CartController::class, 'apiUpdate']);
});


Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('/admin/products/{id}', [ProductController::class, 'update']);
    Route::apiResource('banners', BannerController::class);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/orders', [\App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
    Route::put('/orders/{id}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'update']);


});
