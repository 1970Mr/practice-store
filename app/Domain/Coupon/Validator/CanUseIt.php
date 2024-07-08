<?php

namespace App\Domain\Coupon\Validator;

use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CanUseIt extends BaseCouponValidator
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        if (!Auth::user()->validCoupons()->where('id', $coupon->id)->exists()) {
            throw new InvalidCouponException();
        }

        return parent::validate($coupon);
    }
}
