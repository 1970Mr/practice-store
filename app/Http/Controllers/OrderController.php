<?php

namespace App\Http\Controllers;

use App\Exceptions\VerifyRepeatedException;
use App\Http\Requests\OrderRequest;
use App\Services\Order\Order as OrderService;
use App\Services\Transaction\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
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

    public function callback(Request $request): View
    {
        try {
            $transaction_id = $request->get('Authority');
            $referenceId = $this->transaction->verify($transaction_id);

            return $this->setView('success', __('Payment successfully.'), $referenceId);
        } catch (VerifyRepeatedException $e) {
            $transaction = $this->transaction->getTransactionById($transaction_id);
            return $this->setView('success', __('Payment already done!'), $transaction->reference_id);
        } catch (InvalidPaymentException|InvoiceNotFoundException|Exception $e) {
            return $this->setView('error', __('Payment failed!'));
        }
    }

    public function setView(string $status, string $message, ?int $referenceId = null): View
    {
        return view('transactions.callback', compact(['status', 'message', 'referenceId']));
    }
}
