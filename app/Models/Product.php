<?php

namespace App\Models;

use App\Services\Discount\AmazingSale\AmazingSaleDiscountCalculator;
use App\Services\Discount\CommonDiscount\CommonDiscountCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

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

    public function hasCommonDiscount(): bool
    {
        return $this->commonDiscount()?->hasValidMinimumAmount($this->price);
    }

    public function commonDiscount(): ?CommonDiscount
    {
        return CommonDiscount::validTime()->latest('id')->first();
    }

    public function amazingSale(): HasOne
    {
        return $this->hasOne(AmazingSale::class);
    }
}
