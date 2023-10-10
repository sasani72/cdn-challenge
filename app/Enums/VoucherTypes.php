<?php

namespace App\Enums;

enum VoucherTypes: string
{
    case CHARGE = 'charge';
    case DISCOUNT = 'discount';
}
