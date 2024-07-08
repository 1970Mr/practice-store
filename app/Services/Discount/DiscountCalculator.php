<?php

namespace App\Services\Discount;

use App\Models\Coupon;

class DiscountCalculator
{
    public function discountAmount(Coupon $coupon, int $amount): int
    {
        $discountAmount = ($coupon->percent / 100) * $amount;
        return $coupon->amount_limit ? min($discountAmount, $coupon->amount_limit) : $discountAmount;
    }

    public function discountedPrice(Coupon $coupon, int $amount): int
    {
        return $amount - $this->discountAmount($coupon, $amount);
    }
}
