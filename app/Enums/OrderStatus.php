<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum OrderStatus: string
{
    use EnumValuesTrait;

    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
}
