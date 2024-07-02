<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;
use App\Services\Storage\Contracts\StorageInterface;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// Product
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products/checkout', [ProductController::class, 'checkout'])->name('products.checkout');
Route::get('/products/callback', [ProductController::class, 'callback'])->name('products.callback');

// Purchase
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

// Transaction
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

// Membership
Route::get('/membership-plans', [MembershipPlanController::class, 'index'])->name('membership-plans.index');
Route::post('/membership-plans/checkout', [MembershipPlanController::class, 'checkout'])->name('membership-plans.checkout');
Route::get('/membership-plans/callback', [MembershipPlanController::class, 'callback'])->name('membership-plans.callback');

// Product
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'delete'])->name('cart.delete');
// Temporary route
Route::get('/cart/clear', static function (StorageInterface $storage) {
    $storage->clear();
})->name('cart.clear');

// Order
Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::any('/orders/callback/{transaction:internal_code}', [OrderController::class, 'callback'])->name('orders.callback')->withoutMiddleware(VerifyCsrfToken::class);
