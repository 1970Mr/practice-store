<?php

namespace App\Services\Discount\Common;

use App\Models\CommonDiscount;

class CommonDiscountCalculator
{
    public function discountAmount(CommonDiscount $commonDiscount, int $amount): int
    {
        $discountAmount = ($commonDiscount->percent / 100) * $amount;
        return $commonDiscount->discount_ceiling ?
            min($discountAmount, $commonDiscount->discount_ceiling) :
            $discountAmount;
    }

    public function discountedPrice(CommonDiscount $commonDiscount, int $amount): int
    {
        return $amount - $this->discountAmount($commonDiscount, $amount);
    }
}
