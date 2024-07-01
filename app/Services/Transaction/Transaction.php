<?php

namespace App\Services\Transaction;

use App\Enums\Status;
use App\Exceptions\VerifyRepeatedException;
use App\Models\Purchase;
use App\Models\Transaction as TransactionModel;
use App\Services\Transaction\Contracts\ProductInterface;
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
    public function checkout(Invoice $invoice, ProductInterface $product, string $callbackUrl): mixed
    {
        try {
            $transaction = $this->createTransaction($invoice, $product);
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
            $this->createPurchase($transaction);

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

    private function createTransaction(Invoice $invoice, ProductInterface $product): TransactionModel
    {
        return TransactionModel::query()->create([
            'payment_id' => uniqid('', true),
            'amount' => $invoice->getAmount(),
            'product_type' => get_class($product),
            'product_id' => $product->id,
            'status' => Status::Pending,
            'user_id' => Auth::id(),
        ]);
    }

    private function processPayment(Invoice $invoice, string $callbackUrl, TransactionModel $transaction): mixed
    {
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
        abort_if(is_null($transaction), 404, 'Transaction not found');
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
            'status' => Status::Success,
            'reference_id' => $receipt->getReferenceId(),
        ]);
    }

    private function createPurchase(TransactionModel $transaction): void
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
        if ($transaction->status !== Status::Success) {
            $transaction->update(['status' => Status::Failed]);
        }
    }
}
