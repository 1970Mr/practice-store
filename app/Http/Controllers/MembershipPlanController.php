<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\MembershipPlan;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::all();
        return view('membership_plans.index', compact('plans'));
    }

    public function store(Request $request): mixed
    {
        try {
            $membershipPlan = MembershipPlan::query()->findOrFail($request->plan_id);
            $invoice = (new Invoice())->amount($membershipPlan->price);
            $transaction = Transaction::query()->create([
                'payment_id' => uniqid('', true),
                'amount' => $invoice->getAmount(),
                'product_type' => get_class($membershipPlan),
                'product_id' => $membershipPlan->id,
                'invoice_details' => $invoice,
                'status' => Status::Pending,
                'user_id' => Auth::id(),
            ]);
            Payment::callbackUrl(route('membership-plan.callback'));

            $payment = Payment::purchase($invoice, static function ($driver, $transactionId) use ($transaction) {
                $transaction->update([
                    'transaction_id' => $transactionId,
                ]);
            });

            return $payment->pay()->render();
        } catch (\Exception $e) {
            logger($e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function callback(Request $request): View
    {
        try {
            $transaction_id = $request->get('Authority');
            $transaction = Transaction::query()->where('transaction_id', $transaction_id)->first();
            $receipt = Payment::amount($transaction?->amount)->transactionId($transaction_id)->verify();
            $transaction->update([
                'status' => Status::Success,
                'transaction_result' => $receipt,
                'reference_id' => $receipt->getReferenceId(),
            ]);
            Purchase::query()->create([
                'user_id' => Auth::id(),
                'product_type' => $transaction->product_type,
                'product_id' => $transaction->product_id,
            ]);
            Auth::user()->update([
                'membership_plan_id' => $transaction->product_id,
                'membership_expires_at' => now()->addDays($transaction->product->duration),
            ]);
            return view('transactions.callback', ['status' => 'success', 'transaction_id' => $transaction_id]);
        } catch (InvalidPaymentException|InvoiceNotFoundException $e) {
            logger($e->getMessage());
            $transaction_id = $request->get('Authority');
            $transaction = Transaction::query()->where('transaction_id', $transaction_id)->first();
            $transaction?->update([
                'status' => Status::Failed,
            ]);
            return view('transactions.callback', ['status' => 'error']);
        }
    }
}
