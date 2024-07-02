<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum Status: string
{
    use EnumValuesTrait;

    case SUCCESS = 'success';
    case PENDING = 'pending';
    case FAILED = 'failed';
}
