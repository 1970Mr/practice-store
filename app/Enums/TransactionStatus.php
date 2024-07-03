<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum TransactionStatus: string
{
    use EnumValuesTrait;

    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
}
