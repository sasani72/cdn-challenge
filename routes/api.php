<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [UserController::class, 'login']);

Route::apiResource('vouchers', VoucherController::class)->only('store');

Route::post('vouchers/redeem', [VoucherController::class, 'redeem']);
