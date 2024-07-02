<?php

namespace App\Http\Controllers;

use App\Exceptions\QuantityExceededException;
use App\Http\Requests\CartRequest;
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
        $cartSubtotal = $this->cart->subtotal();
        $transportationCosts = Order::TRANSPORTATION_COSTS;
        $cartTotal = $this->cart->total($transportationCosts);
        return view('cart.index', compact(['cartItems', 'cartSubtotal', 'cartTotal', 'transportationCosts']));
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

    public function update(CartRequest $request, Product $product): RedirectResponse
    {
        try {
            $this->cart->set($product, $request->get('quantity'));
            return back()->with('success', __('The shopping cart has been updated.'));
        } catch (QuantityExceededException $e) {
            return back()->with('error', __('Quantity exceeded!'));
        }
    }

    public function delete(Product $product): RedirectResponse
    {
        $this->cart->delete($product);
        return back()->with('success', __('The product was removed from the cart.'));
    }
}
