<?php

namespace App\Domain\Cost;

use App\Domain\Cost\Contracts\CostInterface;
use App\Models\Coupon;
use App\Services\Discount\DiscountCalculator;

readonly class DiscountCouponCost implements CostInterface
{
    public function __construct(
         private CostInterface $cost,
        private DiscountCalculator $discountCalculator
    )
    {
    }

    public function calculateCost(): int
    {
        $cost = 0;
        $coupon = session('coupon');
        if ($coupon) {
            $cost = $this->discountCalculator->discountAmount($coupon, $this->cost->calculateTotalCost());
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

    public function getCostSummary(): array
    {
        if ($this->calculateCost() !== 0) {
            $costSummary = [$this->getDescription() => $this->calculateCost()];
            return array_merge($this->cost->getCostSummary(), $costSummary);
        }
        return $this->cost->getCostSummary();
    }
}
