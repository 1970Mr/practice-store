<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = Purchase::with('product')
            ->where('user_id', Auth::id())
            ->where('product_type', Product::class)
            ->latest()
            ->get();
        return view('purchases.index', compact('purchases'));
    }
}
