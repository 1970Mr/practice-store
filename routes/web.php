<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;
use App\Services\Storage\Contracts\StorageInterface;
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

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'update'])->name('cart.delete');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
// Temporary route
Route::get('/cart/clear', static function (StorageInterface $storage) {
    $storage->clear();
})->name('cart.clear');

