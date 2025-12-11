
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;

use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;   // 👈 shop orders
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\Shop\SearchController;
use App\Http\Controllers\Shop\BlogController;
use App\Http\Controllers\Shop\ContactController;
use App\Http\Controllers\Shop\AboutController;
use App\Http\Controllers\Shop\CategoryController as ShopCategoryController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\UploadController;

use App\Http\Controllers\API\PaymentIntentController; // Stripe (your big controller)

// Debug helper: clear cart
Route::get('/debug-clear-cart', function () {
    session()->forget('cart');
    return redirect('/cart')->with('success', 'Cart cleared! Now add products again.');
});

// SHOP HOME
Route::get('/', [HomeController::class, 'index'])->name('shop.home');

// CART PAGE
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// GUEST AUTH ROUTES
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/send-code', [LoginController::class, 'sendCode'])->name('login.sendCode');
    Route::post('/login/verify-code', [LoginController::class, 'verifyCode'])->name('login.verifyCode');

    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Google Auth
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
        ->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('auth.google.callback');
});

// ADMIN ROUTES
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('products',   AdminProductController::class);
        Route::post('products/{product}/remove-image', [AdminProductController::class, 'removeImage'])->name('products.remove-image');
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('orders',     AdminOrderController::class);
        Route::resource('customers',  AdminCustomerController::class);
    });

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// SHOP FRONT ROUTES
Route::get('/products', [ShopProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ShopProductController::class, 'show'])->name('products.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// CART ACTION ROUTES (auth + guests)
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}',      [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}',     [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',      [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count',       [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/items',       [CartController::class, 'items'])->name('cart.items');

// AUTHENTICATED USER ROUTES
Route::middleware('auth')->group(function () {
    // Orders (shop)
    Route::get('/orders',                         [ShopOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}',           [ShopOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{orderNumber}/invoice',   [ShopOrderController::class, 'invoice'])->name('orders.invoice');

    // Profile
    Route::get('/profile',  [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Wishlist
    Route::post('/wishlist/{product}',        [WishlistController::class, 'store'])->name('wishlist.add');
    Route::delete('/wishlist/{product}',      [WishlistController::class, 'destroy'])->name('wishlist.remove');
    Route::delete('/wishlist-clear',          [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::get('/wishlist',                   [WishlistController::class, 'index'])->name('wishlist.index');
});

// Guest order tracking
Route::match(['get', 'post'], '/track-order', [ShopOrderController::class, 'track'])->name('orders.track');

// CHECKOUT VIEWS
Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/checkout/success', function () {
    return view('success');
})->name('checkout.success');
Route::post('/sync-order', [PaymentIntentController::class, 'syncAfterSuccess'])
    ->name('order.sync');
// STRIPE ROUTES (used by JS + webhook)
Route::post('/create-payment-intent', [PaymentIntentController::class, 'create'])
    ->name('payment-intent.create');

Route::post('/order-by-payment-intent', [PaymentIntentController::class, 'getOrderByPaymentIntent'])
    ->name('order.by-payment-intent');

Route::post('/stripe/webhook', [PaymentIntentController::class, 'webhook'])
    ->name('stripe.webhook');

// CATEGORIES (shop)
Route::get('/categories',        [ShopCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [ShopCategoryController::class, 'show'])->name('categories.show');

// UPLOAD
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
