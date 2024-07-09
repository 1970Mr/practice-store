<?php

namespace App\Services\Discount;

use App\Enums\CouponType;
use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class DiscountCouponCalculator
{
    /**
     * @throws InvalidCouponException
     */
    public function discountAmount(Coupon $coupon, int $amount): int
    {
        if ($coupon->amount_type === CouponType::PERCENT) {
            $percent = $coupon->amount;
            $discountAmount = ($percent / 100) * $amount;
        } elseif ($coupon->amount > $amount) {
            throw new InvalidCouponException();
        } else {
            $discountAmount = $coupon->amount;
        }

        return $coupon->discount_ceiling ? min($discountAmount, $coupon->discount_ceiling) : $discountAmount;
    }

    public function discountedPrice(Coupon $coupon, int $amount): int
    {
        return $amount - $this->discountAmount($coupon, $amount);
    }
}
