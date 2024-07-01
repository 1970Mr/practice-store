<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::post('/products/checkout', [ProductController::class, 'checkout'])->name('products.checkout');
Route::get('/products/callback', [ProductController::class, 'callback'])->name('products.callback');

Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

Route::get('/membership-plans', [MembershipPlanController::class, 'index'])->name('membership-plans.index');
Route::post('/membership-plans/checkout', [MembershipPlanController::class, 'checkout'])->name('membership-plans.checkout');
Route::get('/membership-plans/callback', [MembershipPlanController::class, 'callback'])->name('membership-plans.callback');

Route::get('/cart-items', [CartController::class, 'index'])->name('cart-items.index');
Route::post('/cart-items/add', [CartController::class, 'add'])->name('cart-items.add');
Route::post('/cart-items/decrement', [CartController::class, 'decrement'])->name('cart-items.decrement');
