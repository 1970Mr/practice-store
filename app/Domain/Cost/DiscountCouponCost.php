<?php

namespace App\Domain\Cost;

use App\Domain\Cost\Contracts\CostInterface;
use App\Domain\Cost\Traits\CostTrait;
use App\Services\Discount\Coupon\CouponDiscountCalculator;

readonly class DiscountCouponCost implements CostInterface
{
    use CostTrait;

    public function __construct(private CostInterface $cost)
    {
    }

    public function calculateCost(): int
    {
        $cost = 0;
        $coupon = session('coupon');
        if ($coupon) {
            $discountCouponCalculator = new CouponDiscountCalculator($coupon);
            $cost = $discountCouponCalculator->discountAmount($this->cost->calculateTotalCost());
        }
        return $cost;
    }

    public function calculateTotalCost(): int
    {
        return $this->cost->calculateTotalCost() - $this->calculateCost();
    }

    public function getDescription(): string
    {
        return "Discount Coupon Cost";
    }
}
