<?php

namespace App\Domain\Cost;

use App\Domain\Cost\Contracts\CostInterface;
use App\Domain\Cost\Traits\CostTrait;

readonly class ShippingCost implements CostInterface
{
    use CostTrait;

    private const SHIPPING_COST = 10000;

    public function __construct(private CostInterface $cost)
    {
    }

    public function calculateCost(): int
    {
        return self::SHIPPING_COST;
    }

    public function calculateTotalCost(): int
    {
        return $this->cost->calculateTotalCost() + $this->calculateCost();
    }

    public function getDescription(): string
    {
        return "Shipping Cost";
    }
}
