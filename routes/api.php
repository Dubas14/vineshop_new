<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ProductImageImportController;
use App\Http\Controllers\Api\Admin\ProductImportController;
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

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Products routes
    Route::apiResource('products', ProductController::class);
    Route::post('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{product}/image', [ProductController::class, 'destroyImage']);
    Route::delete('product-images/{image}', [ProductController::class, 'destroyGalleryImage']);

    // Categories routes - specific first
    Route::get('categories/all-flat', [CategoryController::class, 'allFlat'])->name('admin.categories.all-flat');
    Route::get('categories', [CategoryController::class, 'index']);

    // Parameterized routes last
    Route::get('categories/{id}', [CategoryController::class, 'show'])->where('id', '[0-9]+');
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    Route::post('categories', [CategoryController::class, 'store']);

    // Other admin routes
    Route::apiResource('banners', BannerController::class);
    Route::get('orders', [\App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
    Route::get('orders/{id}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
    Route::put('orders/{id}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'update']);

    // Dashboard routes
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('stat/categories', [DashboardController::class, 'categoriesStat']);
    Route::get('stat/orders-per-day', [DashboardController::class, 'ordersPerDay']);

    // Clients routes
    Route::get('clients', [\App\Http\Controllers\Api\Admin\ClientController::class, 'index']);
    Route::delete('clients/{id}', [\App\Http\Controllers\Api\Admin\ClientController::class, 'destroy']);
    Route::put('clients/{id}', [\App\Http\Controllers\Api\Admin\ClientController::class, 'update']);
});

// Public routes
Route::get('/banners', [BannerController::class, 'public']);
Route::post('/products/import/preview', [ProductImportController::class, 'preview']);
Route::post('/products/import/process', [ProductImportController::class, 'import']);
Route::post('/admin/product-images/import', [ProductImageImportController::class, 'import'])
    ->name('admin.product-images.import');
Route::patch('admin/products/{product}/toggle-active', [ProductController::class, 'toggleActive']);

// Cart routes
Route::middleware('web')->group(function () {
    Route::get('/cart', [CartController::class, 'apiIndex']);
    Route::put('/cart/update/{id}', [CartController::class, 'apiUpdate']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'apiRemove']);
});

// Locale route
Route::post('/set-locale', function () {
    $locale = request('locale');
    if (!in_array($locale, config('app.locales', ['en', 'uk']), true)) {
        return response()->json(['message' => 'Invalid locale'], 400);
    }

    return response()->json(['locale' => $locale])
        ->cookie('locale', $locale, 60 * 24 * 365, '/', null, false, false);
});
