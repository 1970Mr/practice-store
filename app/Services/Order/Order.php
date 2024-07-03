<?php

namespace App\Services\Order;

use App\Models\Order as OrderModel;
use App\Services\Cart\Cart;
use Illuminate\Support\Facades\Auth;

readonly class Order
{
    public function __construct(private Cart $cart)
    {
    }

    public function makeOrder(): OrderModel
    {
        $order = OrderModel::query()->create([
            'amount' => $this->cart->total(OrderModel::TRANSPORTATION_COSTS),
            'user_id' => Auth::id(),
        ]);
        $order->products()->attach($this->getProducts());
        return $order;
    }

    private function getProducts(): array
    {
        $products = [];
        foreach ($this->cart->all() as $product) {
            $products[] = [
                'product_id' => $product->id,
                'quantity' => $product->quantity,
            ];
        }
        return $products;
    }
}
