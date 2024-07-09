<?php

namespace App\Services\Discount\CommonDiscount;

use App\Models\CommonDiscount;
use App\Services\Discount\Contracts\AbstractDiscountCalculator;

readonly class CommonDiscountCalculator Extends AbstractDiscountCalculator
{
    public function __construct(private CommonDiscount $discount)
    {
    }

    public function discountAmount(int $amount): int
    {
        $discountAmount = ($this->discount->percent / 100) * $amount;
        return $this->discount->discount_ceiling ?
            min($discountAmount, $this->discount->discount_ceiling) :
            $discountAmount;
    }
}
