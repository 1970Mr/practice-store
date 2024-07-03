<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use App\Exceptions\QuantityExceededException;
use App\Exceptions\VerifyRepeatedException;
use App\Http\Requests\TransactionRequest;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\Cart\Cart;
use App\Services\Order\Order as OrderService;
use App\Services\Transaction\Transaction as TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;

class TransactionController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly TransactionService $transactionService,
    )
    {
    }

    public function checkout(TransactionRequest $request): mixed
    {
        DB::beginTransaction();
        try {
            $order = $this->orderService->makeOrder();
            $transaction = $this->transactionService->createTransaction(
                $order,
                $request->get('payment_method'),
                $request->get('payment_gateway'),
            );

            // If the payment is not online
            if ($request->payment_method !== PaymentMethod::ONLINE->value) {
                $this->orderService->orderCompletion($transaction->order);
                DB::commit();
                return to_route('home')->with(['success' => __('Your order has been successfully placed.')]);
            }

            $invoice = (new Invoice())->amount($order->amount);
            $callbackUrl = route('transactions.callback', $transaction->internal_code);

            DB::commit();
            return $this->transactionService->processPayment($transaction, $invoice, $callbackUrl);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(['error' => __('Payment failed!')]);
        }
    }

    public function callback(Request $request, Transaction $transaction): View
    {
        try {
            // To verify that the transaction belongs to the user
            $this->transactionService->ensureTransactionBelongsToUser($transaction->transaction_id);
            $transaction->callback()->firstOrCreate( ['callback_payload' => $request->all()] );
            $referenceId = $this->transactionService->verify($transaction);

            $this->orderService->orderCompletion($transaction->order);
            return $this->setView('success', __('Payment successfully.'), $referenceId);
        } catch (VerifyRepeatedException $e) {
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
