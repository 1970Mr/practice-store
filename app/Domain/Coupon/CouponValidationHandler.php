<?php

namespace App\Domain\Coupon;

use App\Domain\Coupon\Validator\BelongsToUser;
use App\Domain\Coupon\Validator\HasUsageLimit;
use App\Domain\Coupon\Validator\IsValidExpiration;
use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class CouponValidationHandler
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        $isValidExpiration = resolve(IsValidExpiration::class);
        $canUseIt = resolve(BelongsToUser::class);
        $hasUsageLimit = resolve(HasUsageLimit::class);

        $isValidExpiration->setNextValidator($canUseIt);
        $canUseIt->setNextValidator($hasUsageLimit);

        return $isValidExpiration->validate($coupon);
    }
}
