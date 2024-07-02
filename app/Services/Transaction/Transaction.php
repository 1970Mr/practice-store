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

class Transaction
{
    /**
     * @throws Exception
     */
    public function checkout(Invoice $invoice, TransactionModel $transaction, string $callbackUrl): mixed
    {
        try {
            return $this->processPayment($invoice, $callbackUrl, $transaction);
        } catch (Exception $e) {
            logger($e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws InvoiceNotFoundException
     * @throws InvalidPaymentException
     * @throws VerifyRepeatedException
     */
    public function verify(string $transactionId, ?callable $callbackFunc = null): int
    {
        try {
            $transaction = $this->getTransactionById($transactionId);
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
            $this->updateTransactionFailure($transactionId);
            throw $e;
        }
    }

    /**
     * Create a new transaction for an order.
     *
     * @param Order $order
     * @param int|null $amount  The transaction amount (optional). If this value is null, the default amount from the order ($order->amount) is used.
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

    private function processPayment(Invoice $invoice, string $callbackUrl, TransactionModel $transaction): mixed
    {
        Payment::via($transaction->gateway);
        Payment::callbackUrl($callbackUrl);
        $payment = Payment::purchase($invoice, static function ($driver, $transactionId) use ($transaction) {
            $transaction->update(['transaction_id' => $transactionId]);
        });

        return $payment->pay()->render();
    }

    public function getTransactionById(string $transactionId): TransactionModel
    {
        /** @var TransactionModel $transaction */
        $transaction = TransactionModel::query()
            ->where('transaction_id', $transactionId)
            ->where('user_id', Auth::id())
            ->first();
        abort_if(is_null($transaction), 404, 'Transaction not found!');
        return $transaction;
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

    private function updateTransactionFailure(string $transactionId): void
    {
        $transaction = $this->getTransactionById($transactionId);
        if ($transaction->status !== Status::SUCCESS) {
            $transaction->update(['status' => Status::FAILED]);
        }
    }
}
