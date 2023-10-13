<?php

namespace App\Repositories;

use App\Models\Voucher;

class VoucherRepository
{
    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return Voucher::create($data);
    }

    /**
     * @param $code
     * @return mixed
     */
    public function checkValid($code)
    {
        return Voucher::whereCode($code)->isValid()->get();
    }
}
