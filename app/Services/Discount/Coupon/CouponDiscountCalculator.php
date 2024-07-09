<?php

namespace App\Services\Discount\Coupon;

use App\Enums\CouponType;
use App\Models\Coupon;
use App\Services\Discount\Contracts\AbstractDiscountCalculator;

readonly class CouponDiscountCalculator Extends AbstractDiscountCalculator
{
    public function __construct(private Coupon $discount)
    {
    }

    public function discountAmount(int $amount): int
    {
        if ($this->discount->amount_type === CouponType::PERCENT) {
            $percent = $this->discount->amount;
            $discountAmount = ($percent / 100) * $amount;
        } else {
            $discountAmount = $this->discount->amount;
        }

        return $this->discount->discount_ceiling ?
            min($discountAmount, $this->discount->discount_ceiling) :
            $discountAmount;
    }
}
