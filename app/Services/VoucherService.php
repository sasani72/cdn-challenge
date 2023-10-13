<?php

namespace App\Services;

use App\Exceptions\InvalidVoucherException;
use App\Exceptions\RedeemedVoucherException;
use App\Jobs\ProcessDeposit;
use App\Models\User;
use App\Models\Voucher;
use App\Repositories\VoucherRepository;

class VoucherService
{

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Voucher::create($data);
    }

    /**
     * @param User $user
     * @param String $code
     */
    public function redeem(User $user, String $code)
    {

        \DB::transaction(function () use ($code, $user) {

            $voucher = Voucher::lockForUpdate()->whereCode($code)->isValid()->first();
            if (!$voucher) {
                throw new InvalidVoucherException("The voucher with code $code is not valid.");
            }

            $hasUsed = $user->vouchers->contains(function ($v) use ($voucher) {
                return $v->id === $voucher->id;
            });

            if ($hasUsed) {
                throw new RedeemedVoucherException("Voucher code $code has been redeemed before.");
            }

            $voucher->increment('current_uses');
            $user->vouchers()->attach($voucher->id, ['redeemed_at' => now()]);

            ProcessDeposit::dispatch($voucher->amount, $user->wallet);
        });
    }
}
