<?php

namespace App\Http\Controllers;

use App\Exceptions\VerifyRepeatedException;
use App\Models\Product;
use App\Services\Transaction\Contracts\ProductInterface;
use App\Services\Transaction\Transaction;
use Illuminate\Http\Request;
use Exception;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;

class ProductController extends Controller
{
    public function __construct(private readonly Transaction $transactionService)
    {
    }

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function purchase(Request $request): mixed
    {
        try {
            /** @var ProductInterface $product */
            $product = Product::query()->findOrFail($request->product_id);
            $invoice = (new Invoice())->amount($product->price);
            $callbackUrl = route('products.callback');

            return $this->transactionService->checkout($invoice, $product, $callbackUrl);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to purchase!']);
        }
    }

    public function callback(Request $request): View
    {
        try {
            $transaction_id = $request->get('Authority');
            $referenceId = $this->transactionService->verify($transaction_id);

            return $this->setView('success', __('transaction_successfully'), $referenceId);
        } catch (VerifyRepeatedException $e) {
            $transaction = $this->transactionService->getTransactionById($transaction_id);
            return $this->setView('success', __('transaction_already_done'), $transaction->reference_id);
        } catch (InvalidPaymentException|InvoiceNotFoundException|Exception $e) {
            return $this->setView('error', __('transaction_failed'));
        }
    }

    public function setView(string $status, string $message, ?int $referenceId = null): View
    {
        return view('transactions.callback', compact(['status', 'message', 'referenceId']));
    }
}
