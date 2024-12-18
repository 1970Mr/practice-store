<?php

namespace App\Domain\Coupon\Validator;

use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class BelongsToUser extends BaseCouponValidator
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        $userHasCoupon = Auth::user()->validCoupons()->where('id', $coupon->id)->exists();
        if ($coupon->user_id !== null && !$userHasCoupon) {
            throw new InvalidCouponException();
        }

        return parent::validate($coupon);
    }
}
