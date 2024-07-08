<?php

namespace App\Domain\Cost;

use App\Domain\Cost\Contracts\CostInterface;
use App\Models\Coupon;
use App\Services\Discount\DiscountCalculator;

readonly class DiscountCost implements CostInterface
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
        $couponCode = session('coupon.code');
        if ($couponCode) {
            /** @var Coupon $coupon */
            $coupon = Coupon::query()->where('code', $couponCode)->first();
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
        return "Discount Cost";
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
