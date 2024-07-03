<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->where('stock', '>=', 1)->get();
        return view('products.index', compact('products'));
    }
}
