<?php

namespace App\Models;

use App\Services\Discount\CommonDiscount\CommonDiscountCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

        $commonDiscount = CommonDiscount::query()
            ->where('minimum_amount', '<=', $this->price)
            ->validTime()
            ->latest()
            ->first();
        return (new CommonDiscountCalculator($commonDiscount))->discountedPrice($this->price);
    }

    public function hasDiscount(): bool
    {
        return CommonDiscount::query()
            ->where('minimum_amount', '<=', $this->price)
            ->validTime()
            ->latest()
            ->exists();
    }
}
