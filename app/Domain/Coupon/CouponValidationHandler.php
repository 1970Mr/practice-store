<?php

namespace App\Domain\Coupon;

use App\Domain\Coupon\Validator\BelongsToUser;
use App\Domain\Coupon\Validator\HasNotExceededUsageLimit;
use App\Domain\Coupon\Validator\HasValidFixedAmount;
use App\Domain\Coupon\Validator\HasValidMinimumAmount;
use App\Domain\Coupon\Validator\HasValidTime;
use App\Domain\Coupon\Validator\IsCouponInSession;
use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class CouponValidationHandler
{
    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        $isCouponInSession = resolve(IsCouponInSession::class);
        $hasValidTime = resolve(HasValidTime::class);
        $belongsToUser = resolve(BelongsToUser::class);
        $hasNotExceededUsageLimit = resolve(HasNotExceededUsageLimit::class);
        $hasValidFixedAmount = resolve(HasValidFixedAmount::class);
        $hasValidMinimumAmount = resolve(HasValidMinimumAmount::class);

        $isCouponInSession->setNextValidator($hasValidTime);
        $hasValidTime->setNextValidator($belongsToUser);
        $belongsToUser->setNextValidator($hasNotExceededUsageLimit);
        $hasNotExceededUsageLimit->setNextValidator($hasValidFixedAmount);
        $hasValidFixedAmount->setNextValidator($hasValidMinimumAmount);

        return $isCouponInSession->validate($coupon);
    }
}
