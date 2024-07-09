<?php

namespace App\Services\Discount\Contracts;

abstract readonly class AbstractDiscountCalculator
{
    abstract public function discountAmount(int $amount): int;

    public function discountedPrice(int $amount): int
    {
        return $amount - $this->discountAmount($amount);
    }
}
