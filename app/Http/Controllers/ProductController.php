<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;

class ProductController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
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
            $product = Product::query()->findOrFail($request->product_id);
            $invoice = (new Invoice())->amount($product->price);
            $callbackUrl = route('products.callback');

            return $this->transactionService->purchase($invoice, $product, $callbackUrl);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to purchase!']);
        }
    }

    public function callback(Request $request): View
    {
        try {
            $transaction_id = $request->get('Authority');
            $this->transactionService->callback($transaction_id);

            return view('transactions.callback', ['status' => 'success', 'transaction_id' => $transaction_id]);
        } catch (InvalidPaymentException|InvoiceNotFoundException $e) {
            return view('transactions.callback', ['status' => 'error']);
        }
    }
}
