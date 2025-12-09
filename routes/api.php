<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PaymentIntentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\WishlistController;

// Public APIs
Route::get('/products',        [ProductController::class, 'index']);
Route::get('/products/{id}',   [ProductController::class, 'show']);
Route::get('/categories',      [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']); // optional
Route::post('/create-payment-intent', [PaymentIntentController::class, 'store']);
Route::post('/upload',                 [UploadController::class, 'store']);

// Protected APIs (require login)
Route::middleware('auth:sanctum')->group(function () {
    // cart
    Route::post('/cart',   [CartController::class, 'store']);   // add / update
    Route::delete('/cart', [CartController::class, 'destroy']); // remove one item

    // wishlist
    Route::get('/wishlist',        [WishlistController::class, 'index']);
    Route::post('/wishlist',       [WishlistController::class, 'store']);
    Route::delete('/wishlist',     [WishlistController::class, 'destroy']);

    // profile
    Route::get('/user/profile',  [ProfileController::class, 'show']);
    Route::post('/user/profile', [ProfileController::class, 'update']);

    // user orders (/api/orders/my)
    Route::get('/orders/my', [OrderController::class, 'myOrders']);

    // admin-ish (same behavior as your Next.js admin APIs)
    Route::post('/products',      [ProductController::class, 'store']);
    Route::put('/products/{id}',  [ProductController::class, 'update']);
    Route::delete('/products',    [ProductController::class, 'destroyByQuery']); // ?id=...
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::post('/categories',        [CategoryController::class, 'store']);
    Route::put('/categories/{id}',    [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});
