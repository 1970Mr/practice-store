<?php

namespace App\Traits;

use App\Models\AmazingSale;
use App\Services\Discount\AmazingSale\AmazingSaleDiscountCalculator;
use App\Services\Discount\CommonDiscount\CommonDiscountCalculator;

trait HasDiscount
{
    public function discountedPrice(): int
    {
        if (!$this->hasDiscount()) {
            return $this->price;
        }

        if ($this->hasCommonDiscount()) {
            return $this->commonDiscountPrice();
        }

        return $this->amazingSalePrice();
    }

    public function hasDiscount(): bool
    {
        return $this->hasCommonDiscount() || $this->hasAmazingSale();
    }

    public function hasCommonDiscount(): bool
    {
        return (bool)$this->commonDiscount()?->hasValidMinimumAmount($this->price);
    }

    public function commonDiscountPrice(): int
    {
        if (!$this->hasCommonDiscount()) {
            return $this->price;
        }
        $commonDiscount = $this->commonDiscount();
        return (new CommonDiscountCalculator($commonDiscount))->discountedPrice($this->price);
    }

    public function hasAmazingSale(): bool
    {
        return $this->amazingSale()->exists();
    }

    public function amazingSalePrice(): int
    {
        if (!$this->hasAmazingSale()) {
            return $this->price;
        }
        /** @var AmazingSale $amazingSale */
        $amazingSale = $this->amazingSale()->first();
        return (new AmazingSaleDiscountCalculator($amazingSale))->discountedPrice($this->price);
    }
}
