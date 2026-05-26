<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// SEO — dynamic sitemap (robots.txt is served statically from public/)
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// AI support chatbot (rate-limited to keep API costs in check)
Route::post('/ai/chat', [AiChatController::class, 'chat'])
    ->middleware('throttle:20,1')->name('ai.chat');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
// Stripe payment flow: create PaymentIntent → (client confirms) → server confirms
Route::post('/checkout/payment-intent', [CheckoutController::class, 'createIntent'])->name('checkout.intent');
Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/dashboard', function () {
    return redirect()->route('orders.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->middleware('verified')->name('orders.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin panel — where YOU see all customer orders
Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminOrderController::class, 'index'])->name('dashboard');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Product management
    Route::post('/products/ai-generate', [AdminProductController::class, 'aiGenerate'])->name('products.ai');
    Route::resource('products', AdminProductController::class)->except('show');
    // Category management
    Route::resource('categories', AdminCategoryController::class)->except('show');

    // Contact messages inbox
    Route::get('/messages', [AdminOrderController::class, 'messages'])->name('messages.index');
    Route::delete('/messages/{message}', [AdminOrderController::class, 'destroyMessage'])->name('messages.destroy');
});

require __DIR__.'/auth.php';
