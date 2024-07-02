<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum PaymentMethod: string
{
    use EnumValuesTrait;

    case ONLINE = 'online';
    case CASH = 'cash';
    case CARD_TO_CARD = 'card_to_card';
}
