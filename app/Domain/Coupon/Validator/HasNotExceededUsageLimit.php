<?php

namespace App\Domain\Coupon\Validator;

use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class HasNotExceededUsageLimit extends BaseCouponValidator
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        if ($coupon->exceededUsageLimit()) {
            throw new InvalidCouponException();
        }

        return parent::validate($coupon);
    }
}
