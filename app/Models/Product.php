<?php

namespace App\Models;

use App\Services\Discount\DiscountCalculator;
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
        $discountCalculator = new DiscountCalculator();
        /** @var Coupon $coupon */
        $coupon = $this->validCoupons()->first();
        return $discountCalculator->discountedPrice($coupon, $this->price);
    }

    public function hasDiscount(): bool
    {
        return false;
//        return $this->validCoupons()->count() > 0;
    }
}
