<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function isAdmin(): bool
    {
        return $this->role === UserRoles::ADMIN->value;
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'user_voucher')
            ->withPivot('redeemed_at')
            ->using(UserVoucher::class)
            ->as('user_voucher');
    }
}
