<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'code', 'type', 'amount', 'max_uses', 'starts_at', 'expires_at', 'description'
    ];

    public function scopeIsValid($query)
    {
        $date = now()->format('Y-m-d H:i:s');

        return $query
            ->whereColumn('current_uses', '<', 'max_uses')
            ->where('starts_at', '<=', $date)
            ->where('expires_at', '>', $date);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_voucher')
            ->withPivot('redeemed_at')
            ->using(UserVoucher::class)
            ->as('user_voucher');
    }
}
