<?php

namespace App\Enums;

enum Status
{
    case Success;
    case Pending;
    case Failed;

    public static function items(): array
    {
        return [
            self::Success->name,
            self::Pending->name,
            self::Failed->name,
        ];
    }
}
