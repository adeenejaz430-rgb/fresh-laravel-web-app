<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

use App\Http\Controllers\Admin\AdminDashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;

use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\Shop\SearchController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Shop\CartController;
Route::get('/debug-clear-cart', function() {
    session()->forget('cart');
    return redirect('/cart')->with('success', 'Cart cleared! Now add products again.');
});
// 🔹 SHOP HOME
Route::get('/', [HomeController::class, 'index'])->name('shop.home');

// 🔹 CART ROUTE (ADD THIS - PLACE IT EARLY)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// 🔹 GUEST (auth) ROUTES
Route::middleware('guest')->group(function () {
    // login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/send-code', [LoginController::class, 'sendCode'])->name('login.sendCode');
    Route::post('/login/verify-code', [LoginController::class, 'verifyCode'])->name('login.verifyCode');

    // register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // GOOGLE AUTH
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
        ->name('auth.google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('auth.google.callback');
});

// 🔹 ADMIN ROUTES (protected by auth + is_admin)
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('products',   AdminProductController::class);
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('orders',     AdminOrderController::class);
        Route::resource('customers',  AdminCustomerController::class);
    });

// 🔹 LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// 🔹 SHOP FRONT ROUTES
Route::get('/products', [ShopProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ShopProductController::class, 'show'])->name('products.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [ShopOrderController::class, 'index'])->name('orders.index');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

   Route::middleware('auth')->group(function () {
    // Add product to wishlist
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.add');

    // Remove product from wishlist
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
      Route::delete('/wishlist-clear', [WishlistController::class, 'clear'])->name('wishlist.clear');

    // Wishlist page
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});

    // CART ROUTES
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    // routes/web.php
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
});

// 🔹 UPLOAD
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');