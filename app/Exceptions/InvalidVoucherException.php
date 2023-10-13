<?php

namespace App\Exceptions;

use Exception;

class InvalidVoucherException extends Exception
{
    public function render($request)
    {
        return response()->json(['message' => 'The voucher is not valid'], 400);
    }
}
