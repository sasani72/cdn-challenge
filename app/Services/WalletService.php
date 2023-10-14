<?php

namespace App\Services;

use App\Models\Wallet;
use App\Exceptions\NoWalletException;
use Illuminate\Http\Request;

class WalletService
{
    /**
     * @param Request $request
     * @return mixed
     * @throws NoWalletException
     */
    public function getBalance(Request $request)
    {
        $wallet = $this->findWalletByMobile($request->mobile);
        if (!$wallet) {
            throw new NoWalletException("No wallet found for this user");
        }
        return $wallet->balance;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws NoWalletException
     */
    public function getTransactions(Request $request)
    {
        $wallet = $this->findWalletByMobile($request->mobile);
        if (!$wallet) {
            throw new NoWalletException("No wallet found for this user");
        }
        return $wallet->transactions;
    }

    /**
     * @param $mobile
     * @return mixed
     */
    private function findWalletByMobile($mobile)
    {
        return Wallet::whereHas('user', function ($query) use ($mobile) {
            $query->where('mobile', $mobile);
        })->first();
    }
}
