<?php

namespace App\Services\Discount\AmazingSale;

use App\Models\AmazingSale;
use App\Services\Discount\Contracts\AbstractDiscountCalculator;

readonly class AmazingSaleDiscountCalculator Extends AbstractDiscountCalculator
{
    public function __construct(private AmazingSale $discount)
    {
    }

    public function discountAmount(int $amount): int
    {
        return ($this->discount->percent / 100) * $amount;
    }
}
