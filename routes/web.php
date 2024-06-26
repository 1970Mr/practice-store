<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('product');

Route::get('/purchases', [PurchaseController::class, 'index']);
Route::post('/purchases', [PurchaseController::class, 'store']);

Route::get('/transactions', [TransactionController::class, 'index']);

Route::get('/membership-plans', [MembershipPlanController::class, 'index']);
Route::post('/membership-plans/purchase', [MembershipPlanController::class, 'store']);
Route::get('/membership-plans/callback', [MembershipPlanController::class, 'callback'])->name('membership-plan.callback');

Route::post('/cart/add', [CartController::class, 'add']);
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/checkout', [CartController::class, 'checkout']);
