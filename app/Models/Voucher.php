<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'code', 'type', 'amount', 'max_uses', 'starts_at', 'expires_at', 'description'
    ];
}
