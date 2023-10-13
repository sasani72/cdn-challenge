<?php

namespace App\Enums;

enum UserRoles: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
