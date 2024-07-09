<?php

namespace App\Domain\Cost;

use App\Domain\Cost\Contracts\CostInterface;
use App\Services\Cart\Cart;

readonly class DiscountProductsCost implements CostInterface
{
    public function __construct(
        private CostInterface $cost,
        private Cart $cart
    )
    {
    }

    public function calculateCost(): int
    {
        $discountedAmount = 0;
        foreach ($this->cart->all() as $product) {
            if ($product->hasCoupon()) {
                $discountedAmount += $product->price - $product->discountedPrice();
            }
        }
        return $discountedAmount;
    }

    public function calculateTotalCost(): int
    {
        return $this->cost->calculateTotalCost() - $this->calculateCost();
    }

    public function getDescription(): string
    {
        return "Discount Products Cost";
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
