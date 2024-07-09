<?php

namespace App\Domain\Cost\Traits;

trait CostTrait
{
    public function getCostSummary(): array
    {
        if ($this->calculateCost() !== 0) {
            $costSummary = [$this->getDescription() => $this->calculateCost()];
            return array_merge($this->cost->getCostSummary(), $costSummary);
        }
        return $this->cost->getCostSummary();
    }
}
