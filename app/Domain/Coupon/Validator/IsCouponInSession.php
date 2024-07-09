<?php

namespace App\Domain\Coupon\Validator;

use App\Enums\CouponType;
use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class IsCouponInSession extends BaseCouponValidator
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        if (session('coupon')) {
            throw new InvalidCouponException();
        }

        return parent::validate($coupon);
    }
}
