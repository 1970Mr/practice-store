<?php

namespace App\Services\Transaction;

use App\Enums\Status;
use App\Exceptions\VerifyRepeatedException;
use App\Models\Order;
use App\Models\Transaction as TransactionModel;
use Exception;
use Illuminate\Support\Facades\Auth;
use Shetabit\Multipay\Contracts\ReceiptInterface;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Transaction
{
    /**
     * @throws InvoiceNotFoundException
     * @throws InvalidPaymentException
     * @throws VerifyRepeatedException
     */
    public function verify(TransactionModel $transaction, ?callable $callbackFunc = null): int
    {
        try {
            $receipt = $this->verifyPayment($transaction);
            $this->updateTransactionSuccess($transaction, $receipt);

            if ($callbackFunc) {
                $callbackFunc($transaction);
            }

            return $receipt->getReferenceId();
        } catch (InvalidPaymentException|InvoiceNotFoundException|Exception $e) {
            if ($e->getCode() === 101) {
                throw new VerifyRepeatedException();
            }
            logger($e->getMessage());
            $this->updateTransactionFailure($transaction);
            throw $e;
        }
    }

    /**
     * Create a new transaction for an order.
     *
     * @param Order $order
     * @param int|null $amount The transaction amount (optional). If this value is null, the default amount from the order ($order->amount) is used.
     * @return TransactionModel
     */
    public function createTransaction(Order $order, string $paymentMethod, ?string $gateway = null, ?int $amount = null): TransactionModel
    {
        $amount = $amount ?? $order->amount;
        return TransactionModel::query()->create([
            'internal_code' => md5(uniqid('', true)),
            'amount' => $amount,
            'gateway' => $gateway,
            'payment_method' => $paymentMethod,
            'order_id' => $order->id,
            'status' => Status::PENDING,
            'user_id' => Auth::id(),
        ]);
    }

    public function processPayment(TransactionModel $transaction, Invoice $invoice, string $callbackUrl): mixed
    {
        Payment::via($transaction->gateway);
        Payment::callbackUrl($callbackUrl);
        $payment = Payment::purchase($invoice, static function ($driver, $transactionId) use ($transaction) {
            $transaction->update(['transaction_id' => $transactionId]);
        });
        return $payment->pay()->render();
    }

    /**
     * @throws HttpException|NotFoundHttpException
     */
    public function ensureTransactionBelongsToUser(string $transactionId): void
    {
        $transaction = TransactionModel::query()
            ->where('transaction_id', $transactionId)
            ->where('user_id', Auth::id())
            ->exists();
        abort_if(!$transaction, 404, __('Transaction not found!'));
    }

    /**
     * @throws InvoiceNotFoundException
     */
    private function verifyPayment(TransactionModel $transaction): ReceiptInterface
    {
        return Payment::amount($transaction->amount)
            ->transactionId($transaction->transaction_id)
            ->verify();
    }

    private function updateTransactionSuccess(TransactionModel $transaction, ReceiptInterface $receipt): void
    {
        $transaction->update([
            'status' => Status::SUCCESS,
            'reference_id' => $receipt->getReferenceId(),
        ]);
    }

    private function updateTransactionFailure(TransactionModel $transaction): void
    {
        if ($transaction->status !== Status::SUCCESS) {
            $transaction->update(['status' => Status::FAILED]);
        }
    }
}
