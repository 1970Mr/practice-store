<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// Product
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'delete'])->name('cart.delete');

// Transaction
Route::post('/transactions/checkout', [TransactionController::class, 'checkout'])->name('transactions.checkout')
    ->middleware('auth');
Route::any('/transactions/callback/{transaction:internal_code}', [TransactionController::class, 'callback'])->name('transactions.callback')
    ->middleware('auth')
    ->withoutMiddleware(VerifyCsrfToken::class);

// Order
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

// Coupon
Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('coupon.apply');
Route::post('/remove-coupon', [CouponController::class, 'removeCoupon'])->name('coupon.remove');
