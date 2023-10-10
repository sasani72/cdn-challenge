<?php

namespace App\Services;

use App\Repositories\VoucherRepository;

class VoucherService
{
    protected $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }
}
