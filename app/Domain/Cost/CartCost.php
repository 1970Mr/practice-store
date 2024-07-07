<?php

namespace App\Domain\Cost;

use App\Domain\Cost\Contracts\CostInterface;
use App\Services\Cart\Cart;

readonly class CartCost implements CostInterface
{
    public function __construct(private Cart $cart)
    {
    }

    public function calculateCost(): int
    {
        return $this->cart->subtotal();
    }

    public function calculateTotalCost(): int
    {
        return $this->calculateCost();
    }

    public function getDescription(): string
    {
        return "Total Amount";
    }

    public function getCostSummary(): array
    {
        return [$this->getDescription() => $this->calculateCost()];
    }
}
