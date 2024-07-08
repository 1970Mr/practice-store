<?php

namespace App\Domain\Coupon\Validator;

use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class IsValidExpiration extends BaseCouponValidator
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        if ($coupon->isExpired()) {
            throw new InvalidCouponException();
        }

        return parent::validate($coupon);
    }
}
