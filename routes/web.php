<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ManualPaymentWebhookController;
use App\Http\Controllers\PublicPageController;
use App\Http\Middleware\ShareWebsiteSettings;
use App\Services\CartService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::middleware(ShareWebsiteSettings::class)->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items', [CartController::class, 'store'])->middleware('throttle:30,1')->name('cart.store');
    Route::patch('/cart/items', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');
    Route::get('/orders/{order}/{token}', [CheckoutController::class, 'show'])->where(['order' => 'KD-[A-Z0-9-]+', 'token' => '[A-Za-z0-9]{64}'])->name('orders.show');
    Route::get('/cart-count', fn (CartService $cart) => response()->json(['quantity' => $cart->quantity()]))->name('cart.count');
});

Route::post('/webhooks/manual-payment', ManualPaymentWebhookController::class)
    ->withoutMiddleware(ValidateCsrfToken::class)
    ->middleware('throttle:60,1')
    ->name('webhooks.manual-payment');

Route::middleware(ShareWebsiteSettings::class)->controller(PublicPageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/coffee', 'coffee')->name('coffee');
    Route::get('/products', 'products')->name('products.index');
    Route::get('/products/category/{slug}', 'productCategory')->where('slug', '[a-z0-9-]+')->name('products.category');
    Route::get('/products/{slug}', 'product')->where('slug', '[A-Za-z0-9\-]+')->name('products.show');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/journal', 'journal')->name('journal.index');
    Route::get('/journal/{slug}', 'journalPost')->where('slug', '[A-Za-z0-9\-]+')->name('journal.show');
    Route::get('/location', 'location')->name('location');
    Route::get('/contact', 'contact')->name('contact');
});

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/sitemap.xml', [PublicPageController::class, 'sitemap'])->name('sitemap');

Route::get('/{slug}', [PublicPageController::class, 'cmsPage'])
    ->middleware(ShareWebsiteSettings::class)
    ->where('slug', '^(?!admin$|products$|journal$|menu$|gallery$|storage$|up$)[a-z0-9]+(?:-[a-z0-9]+)*$')
    ->name('cms-pages.show');
