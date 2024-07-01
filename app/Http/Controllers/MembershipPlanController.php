<?php

namespace App\Http\Controllers;

use App\Exceptions\VerifyRepeatedException;
use App\Models\MembershipPlan;
use App\Services\Transaction\Contracts\ProductInterface;
use App\Services\Transaction\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;

class MembershipPlanController extends Controller
{
    public function __construct(private readonly Transaction $transactionService)
    {
    }

    public function index()
    {
        $plans = MembershipPlan::all();
        return view('membership_plans.index', compact('plans'));
    }

    public function checkout(Request $request): mixed
    {
        try {
            /** @var ProductInterface $membershipPlan */
            $membershipPlan = MembershipPlan::query()->findOrFail($request->plan_id);
            $invoice = (new Invoice())->amount($membershipPlan->price);
            $callbackUrl = route('membership-plans.callback');

            return $this->transactionService->checkout($invoice, $membershipPlan, $callbackUrl);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to purchase!']);
        }
    }

    public function callback(Request $request): View
    {
        try {
            $transaction_id = $request->get('Authority');
            $referenceId = $this->transactionService->verify($transaction_id, static function ($transaction) {
                Auth::user()->update([
                    'membership_plan_id' => $transaction->product_id,
                    'membership_expires_at' => now()->addDays($transaction->product->duration),
                ]);
            });

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
