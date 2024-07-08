<?php

namespace App\Services\Discount;

use App\Models\Coupon;

class DiscountCalculator
{
    public function discountAmount(Coupon $coupon, int $amount): int
    {
        $discountAmount = ($coupon->percent / 100) * $amount;
        return min($discountAmount, $coupon->limit);
    }

    public function discountedPrice(Coupon $coupon, int $amount): int
    {
        return $amount - $this->discountAmount($coupon, $amount);
    }
}
