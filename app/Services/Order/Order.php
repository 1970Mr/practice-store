<?php

namespace App\Services\Order;

use App\Domain\Cost\Contracts\CostInterface;
use App\Enums\OrderStatus;
use App\Events\OrderCompleted;
use App\Exceptions\QuantityExceededException;
use App\Models\Cost;
use App\Models\Order as OrderModel;
use App\Services\Cart\Cart;
use Illuminate\Support\Facades\Auth;

readonly class Order
{
    public function __construct(
        private Cart $cart,
        private CostInterface $cost,
    )
    {
    }

    public function makeOrder(): OrderModel
    {
        $order = OrderModel::query()->create([
            'amount' => $this->cart->subtotal(),
            'status' => OrderStatus::PENDING->value,
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

    /**
     * @throws QuantityExceededException
     */
    public function orderCompletion(OrderModel $order): void
    {
        if ($order->status === OrderStatus::COMPLETED->value) {
            return;
        }

        // Reduce the quantity of products
        $this->normalizeQuantity($order);

        // Create a cost summary
        $this->createCostSummary($order);

        // Remove coupon code
        session()->forget('coupon');

        // Clear the cart items
        $this->cart->clear();

        $order->update(['status' => OrderStatus::COMPLETED->value]);
        event(new OrderCompleted($order));
    }

    /**
     * @throws QuantityExceededException
     */
    private function normalizeQuantity(OrderModel $order): void
    {
        foreach ($order->products as $product) {
            if ($product->stock < 0) {
                throw new QuantityExceededException(__('Insufficient stock!'));
            }
            $product->decrement('stock', $product->pivot->quantity);
        }
    }

    public function createCostSummary(OrderModel $order): void
    {
        Cost::query()->create([
            'total_cost' => $this->cost->calculateTotalCost(),
            'summary' => $this->cost->getCostSummary(),
            'order_id' => $order->id,
        ]);
    }
}
