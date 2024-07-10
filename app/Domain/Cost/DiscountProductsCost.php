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
        $discountAmount = 0;
        foreach ($this->cart->all() as $product) {
            if ($product->hasDiscount()) {
                $discountAmount += $product->price - $product->discountedPrice();
            }
        }
        return $discountAmount;
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
