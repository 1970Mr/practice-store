<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum PaymentGateway: string
{
    use EnumValuesTrait;

    case ZARINPAL = 'zarinpal';
    case IDPAY = 'idpay';
}
