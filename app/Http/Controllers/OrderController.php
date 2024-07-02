<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\Order\Order as OrderService;
use App\Services\Transaction\Transaction;
use Exception;
use Shetabit\Multipay\Invoice;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly Transaction $transaction,
    )
    {
    }

    public function checkout(OrderRequest $request): mixed
    {
        try {
            $order = $this->orderService->makeOrder();
            $invoice = (new Invoice())->amount($order->amount);
            $callbackUrl = route('orders.callback');

            return $this->transaction->checkout($invoice, $order, $callbackUrl);
        } catch (Exception $e) {
            return back()->with(['error' => __('Payment failed!')]);
        }
    }
}
