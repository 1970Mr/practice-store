<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;

class MembershipPlanController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    public function index()
    {
        $plans = MembershipPlan::all();
        return view('membership_plans.index', compact('plans'));
    }

    public function purchase(Request $request): mixed
    {
        try {
            $membershipPlan = MembershipPlan::query()->findOrFail($request->plan_id);
            $invoice = (new Invoice())->amount($membershipPlan->price);
            $callbackUrl = route('membership-plans.callback');

            return $this->transactionService->purchase($invoice, $membershipPlan, $callbackUrl);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to purchase!']);
        }
    }

    public function callback(Request $request): View
    {
        try {
            $transaction_id = $request->get('Authority');
            $this->transactionService->callback($transaction_id, static function ($transaction) {
                Auth::user()->update([
                    'membership_plan_id' => $transaction->product_id,
                    'membership_expires_at' => now()->addDays($transaction->product->duration),
                ]);
            });

            return view('transactions.callback', ['status' => 'success', 'transaction_id' => $transaction_id]);
        } catch (InvalidPaymentException|InvoiceNotFoundException $e) {
            return view('transactions.callback', ['status' => 'error']);
        }
    }
}
