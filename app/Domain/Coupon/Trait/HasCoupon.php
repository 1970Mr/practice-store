<?php

namespace App\Domain\Coupon\Trait;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCoupon
{
    public function coupons(): MorphMany
    {
        return $this->morphMany(Coupon::class, 'couponable');
    }

    public function validCoupons(): MorphMany
    {
        return $this->coupons()->where('expire_time' , '>' , now());
    }
}
