<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\BannerController;

Route::post('/admin/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/admin/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/admin/user', [AuthController::class, 'user']);
Route::get('/banners', [BannerController::class, 'public']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('banners', BannerController::class);
    Route::get('/categories', [CategoryController::class, 'index']);

});
