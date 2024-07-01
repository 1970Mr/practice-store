<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Storage\Session\SessionStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct()
    {
    }

    public function index(): View
    {
        //
    }

    public function add(Product $product): RedirectResponse
    {
        return back()->with('success', 'Cart added successfully');
    }
}
