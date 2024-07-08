<?php

namespace App\Http\Controllers;

use App\Domain\Coupon\CouponValidationHandler;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Exception;

class CouponController extends Controller
{
    public function __construct(private readonly CouponValidationHandler $couponValidationHandler)
    {
    }

    public function applyCoupon(CouponRequest $request): RedirectResponse
    {
        try {
            /** @var Coupon $coupon */
            $coupon = Coupon::query()->where('code', $request->coupon_code)->firstOrFail();
            $this->couponValidationHandler->validate($coupon);
            session()->put('coupon', [
                'code' => $coupon->code,
                'percent' => $coupon->percent,
                'amount_limit' => $coupon->amount_limit,
            ]);

            return back()->with('success', __('Discount code applied.'));
        } catch (Exception $e) {
            return back()->with('error', __('The discount code is invalid!'));
        }
    }

    public function removeCoupon(): RedirectResponse
    {
        session()->forget('coupon');
        return back()->with('success', __('The discount code has been removed!'));
    }
}
