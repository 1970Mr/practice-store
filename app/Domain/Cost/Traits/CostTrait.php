<?php

namespace App\Domain\Cost\Traits;

trait CostTrait
{
    public function getCostSummary(): array
    {
        $costSummary = [$this->getDescription() => $this->calculateCost()];
        return array_merge($this->cost->getCostSummary(), $costSummary);
    }
}
