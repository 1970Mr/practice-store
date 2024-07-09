<?php

namespace App\Services\Discount\Coupon;

use App\Enums\CouponType;
use App\Models\Coupon;

class DiscountCouponCalculator
{
    public function discountAmount(Coupon $coupon, int $amount): int
    {
        if ($coupon->amount_type === CouponType::PERCENT) {
            $percent = $coupon->amount;
            $discountAmount = ($percent / 100) * $amount;
        } else {
            $discountAmount = $coupon->amount;
        }

        return $coupon->discount_ceiling ?
            min($discountAmount, $coupon->discount_ceiling) :
            $discountAmount;
    }
}
