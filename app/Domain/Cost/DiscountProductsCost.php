<?php

namespace App\Domain\Cost;

use App\Domain\Cost\Contracts\CostInterface;
use App\Domain\Cost\Traits\CostTrait;
use App\Services\Cart\Cart;

readonly class DiscountProductsCost implements CostInterface
{
    use CostTrait;

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
}
