<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum CouponType: string
{
    use EnumValuesTrait;

    case PERCENT = 'percent';
    case FIXED_AMOUNT = 'fixed_amount';
}
