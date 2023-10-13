<?php

namespace App\Exceptions;

use Exception;

class NoWalletException extends Exception
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json(['message' => 'No wallet found for this user'], 400);
    }
}
