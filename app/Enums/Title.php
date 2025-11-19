<?php

namespace App\Enums;

enum Title: string
{
    // includes only examples in the test data
    case MR = 'mr';
    case MRS = 'mrs';
    case MISTER = 'mister';
    case MISS = 'miss';
    case MS = 'ms';
    case DR = 'dr';
    case PROF = 'prof';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
