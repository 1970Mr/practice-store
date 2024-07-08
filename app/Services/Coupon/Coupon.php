<?php

namespace App\Services\Coupon;

class Coupon
{
    public function couponUsed(): void
    {
        $coupon = session('coupon');
        if ($coupon) {
            $coupon->increment('used_count');
            session()->forget('coupon');
        }
    }
}
