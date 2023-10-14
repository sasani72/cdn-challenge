<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\VoucherController;
use App\Http\Controllers\Api\V1\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('login', [UserController::class, 'login']);

    Route::get('wallet/balance', [WalletController::class, 'getBalance']);

    Route::get('wallet/transactions', [WalletController::class, 'getTransactions']);

    Route::apiResource('vouchers', VoucherController::class)->only('store');

    Route::post('vouchers/redeem', [VoucherController::class, 'redeem']);

    Route::get('vouchers/{voucher}/users', [VoucherController::class, 'getVoucherUsers']);
});
