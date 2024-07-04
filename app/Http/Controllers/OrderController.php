<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()->orders()->with('products', 'transactions')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_if($order->user_id !== Auth::id(), 404, __('Order not found!'));
        $order->load('products', 'transactions');
        return view('orders.show', compact('order'));
    }
}
