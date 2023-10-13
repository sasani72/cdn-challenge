<?php

namespace App\Exceptions;

use Exception;

class RedeemedVoucherException extends Exception
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json(['message' => 'The voucher is redeemed before'], 422);
    }
}
