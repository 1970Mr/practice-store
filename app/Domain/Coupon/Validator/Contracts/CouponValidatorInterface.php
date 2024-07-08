<?php

namespace App\Domain\Coupon\Validator\Contracts;

use App\Models\Coupon;

interface CouponValidatorInterface
{
    public function setNextValidator(CouponValidatorInterface $validator): void;

    public function validate(Coupon $coupon): bool;
}
