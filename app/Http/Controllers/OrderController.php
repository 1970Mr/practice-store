<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Exceptions\VerifyRepeatedException;
use App\Http\Requests\OrderRequest;
use App\Models\Transaction;
use App\Services\Order\Order as OrderService;
use App\Services\Transaction\Transaction as TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly TransactionService $transactionService,
    )
    {
    }

    public function checkout(OrderRequest $request): mixed
    {
        DB::beginTransaction();
        try {
            $order = $this->orderService->makeOrder();
            $transaction = $this->transactionService->createTransaction(
                $order,
                $request->get('payment_method'),
                $request->get('payment_gateway'),
            );
            if ($request->payment_method !== PaymentMethod::ONLINE->value) {
                DB::commit();
                return to_route('home')->with(['success' => __('Your order has been successfully placed.')]);
            }

            $invoice = (new Invoice())->amount($order->amount);
            $callbackUrl = route('orders.callback', $transaction->internal_code);

            DB::commit();
            return $this->transactionService->checkout($invoice, $transaction, $callbackUrl);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(['error' => __('Payment failed!')]);
        }
    }

    public function callback(Request $request, Transaction $transaction): View
    {
        try {
            // To verify that the transaction belongs to the user
            $this->transactionService->getTransactionById($transaction->transaction_id);
            $transaction_id = $request->get('Authority');
            $referenceId = $this->transactionService->verify($transaction_id);

            return $this->setView('success', __('Payment successfully.'), $referenceId);
        } catch (VerifyRepeatedException $e) {
            $transaction = $this->transactionService->getTransactionById($transaction->id);
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
