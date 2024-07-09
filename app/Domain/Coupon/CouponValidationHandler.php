<?php

namespace App\Domain\Coupon;

use App\Domain\Coupon\Validator\BelongsToUser;
use App\Domain\Coupon\Validator\HasUsageLimit;
use App\Domain\Coupon\Validator\HasValidAmount;
use App\Domain\Coupon\Validator\HasValidMinimumAmount;
use App\Domain\Coupon\Validator\HasValidTime;
use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class CouponValidationHandler
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        if (session('coupon')) {
            throw new InvalidCouponException();
        }

        $isValidExpiration = resolve(HasValidTime::class);
        $canUseIt = resolve(BelongsToUser::class);
        $hasUsageLimit = resolve(HasUsageLimit::class);
        $hasValidAmount = resolve(HasValidAmount::class);
        $hasValidMinimumAmount = resolve(HasValidMinimumAmount::class);

        $isValidExpiration->setNextValidator($canUseIt);
        $canUseIt->setNextValidator($hasUsageLimit);
        $hasUsageLimit->setNextValidator($hasValidAmount);
        $hasValidAmount->setNextValidator($hasValidMinimumAmount);

        return $isValidExpiration->validate($coupon);
    }
}
