<?php

namespace App\Domain\Cost\Contracts;

interface CostInterface
{
    public function calculateCost(): int;
    public function calculateTotalCost(): int;
    public function getDescription(): string;
    public function getCostSummary(): array;
}
