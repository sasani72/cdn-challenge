<?php

namespace App\Enums;

enum VoucherTypes: string
{
    case CHARGE = 'charge';
    case DISCOUNT = 'discount';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
