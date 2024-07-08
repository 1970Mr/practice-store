<?php

namespace App\Domain\Coupon;

use App\Domain\Coupon\Validator\CanUseIt;
use App\Domain\Coupon\Validator\IsValidExpiration;
use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class CouponValidationHandler
{
    /**
     * @throws InvalidCouponException
     */
    public function validated(Coupon $coupon): bool
    {
        $isValidExpiration = resolve(IsValidExpiration::class);
        $canUseIt = resolve(CanUseIt::class);

        $isValidExpiration->setNextValidator($canUseIt);

        return $isValidExpiration->validate($coupon);
    }
}
