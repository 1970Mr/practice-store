<?php

namespace App\Traits;

trait EnumValuesTrait
{
    public static function values(): array
    {
        return array_map(static fn($case) => $case->value, self::cases());
    }
}
