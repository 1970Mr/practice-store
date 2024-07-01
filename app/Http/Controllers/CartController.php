<?php

namespace App\Http\Controllers;

use App\Exceptions\QuantityExceededException;
use App\Models\Order;
use App\Models\Product;
use App\Services\Cart\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly Cart $cart)
    {
    }

    public function index(): View
    {
        $cartItems = $this->cart->all();
        $transportationCosts = Order::TRANSPORTATION_COSTS;
        $subtotal = $this->cart->subtotal();
        $total = $this->cart->total($transportationCosts);
        return view('cart.index', compact(['cartItems', 'subtotal', 'total', 'transportationCosts']));
    }

    public function add(Product $product): RedirectResponse
    {
        try {
            $this->cart->add($product);
            return back()->with('success', __('Added to cart.'));
        } catch (QuantityExceededException $e) {
            return back()->with('error', __('Quantity exceeded!'));
        }
    }
}
