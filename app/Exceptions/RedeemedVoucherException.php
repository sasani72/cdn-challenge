<?php

namespace App\Exceptions;

use Exception;

class RedeemedVoucherException extends Exception
{
    public function render($request)
    {
        return response()->json(['message' => 'The voucher is redeemed before'], 422);
    }
}
