<?php

namespace App\Domain\Coupon\Validator;

use App\Domain\Cost\Contracts\CostInterface;
use App\Enums\CouponType;
use App\Exceptions\InvalidCouponException;
use App\Models\Coupon;

class HasValidMinimumAmount extends BaseCouponValidator
{
    public function __construct(readonly private CostInterface $cost)
    {
    }

    /**
     * @throws InvalidCouponException
     */
    public function validate(Coupon $coupon): bool
    {
        $orderAmount = $this->cost->calculateTotalCost();

        if ($coupon->minimum_amount > $orderAmount) {
            throw new InvalidCouponException();
        }

        return parent::validate($coupon);
    }
}
