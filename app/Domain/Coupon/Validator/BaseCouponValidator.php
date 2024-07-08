<?php

namespace App\Domain\Coupon\Validator;

use App\Domain\Coupon\Validator\Contracts\CouponValidatorInterface;
use App\Models\Coupon;

class BaseCouponValidator implements CouponValidatorInterface
{
    private ?CouponValidatorInterface $nextValidator = null;

    public function setNextValidator(CouponValidatorInterface $validator): void
    {
        $this->nextValidator = $validator;
    }

    public function validate(Coupon $coupon): bool
    {
        if (!$this->nextValidator) {
            return true;
        }

        return $this->nextValidator->validate($coupon);
    }
}
