<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Purchase;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class TransactionService
{
    /**
     * @throws Exception
     */
    public function purchase(Invoice $invoice, Model $model, string $callbackUrl): mixed
    {
        try {
            $transaction = $this->createTransaction($invoice, $model);
            return $this->processPayment($invoice, $callbackUrl, $transaction);
        } catch (Exception $e) {
            logger($e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws InvoiceNotFoundException
     * @throws InvalidPaymentException
     */
    public function callback(string $transactionId, ?callable $callbackFunc = null): void
    {
        try {
            $transaction = $this->getTransactionById($transactionId);
            $receipt = $this->verifyPayment($transaction);
            $this->updateTransactionSuccess($transaction, $receipt);
            $this->createPurchase($transaction);

            if ($callbackFunc) {
                $callbackFunc($transaction);
            }
        } catch (InvalidPaymentException|InvoiceNotFoundException $e) {
            logger($e->getMessage());
            $this->updateTransactionFailure($transactionId);
            throw $e;
        }
    }

    private function createTransaction(Invoice $invoice, Model $model): Transaction
    {
        return Transaction::query()->create([
            'payment_id' => uniqid('', true),
            'amount' => $invoice->getAmount(),
            'product_type' => get_class($model),
            'product_id' => $model->id,
            'invoice_details' => $invoice,
            'status' => Status::Pending,
            'user_id' => Auth::id(),
        ]);
    }

    private function processPayment(Invoice $invoice, string $callbackUrl, Transaction $transaction): mixed
    {
        Payment::callbackUrl($callbackUrl);
        $payment = Payment::purchase($invoice, static function ($driver, $transactionId) use ($transaction) {
            $transaction->update(['transaction_id' => $transactionId]);
        });

        return $payment->pay()->render();
    }

    private function getTransactionById(string $transactionId): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::query()
            ->where('transaction_id', $transactionId)
            ->where('user_id', Auth::id())
            ->first();
        abort_if(is_null($transaction), 404, 'Transaction not found');
        return $transaction;
    }

    private function verifyPayment(Transaction $transaction): mixed
    {
        return Payment::amount($transaction->amount)
            ->transactionId($transaction->transaction_id)
            ->verify();
    }

    private function updateTransactionSuccess(Transaction $transaction, $receipt): void
    {
        $transaction->update([
            'status' => Status::Success,
            'transaction_result' => $receipt,
            'reference_id' => $receipt->getReferenceId(),
        ]);
    }

    private function createPurchase(Transaction $transaction): void
    {
        Purchase::query()->create([
            'user_id' => Auth::id(),
            'product_type' => $transaction->product_type,
            'product_id' => $transaction->product_id,
        ]);
    }

    private function updateTransactionFailure(string $transactionId): void
    {
        $transaction = $this->getTransactionById($transactionId);
        $transaction?->update(['status' => Status::Failed]);
    }
}
