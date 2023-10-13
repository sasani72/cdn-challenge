<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TransactionResource;
use App\Rules\IranMobileRule;
use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected $walletService;

    /**
     * WalletController constructor.
     * @param WalletService $walletService
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\NoWalletException
     */
    public function getBalance(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'string', new IranMobileRule()]
        ]);

        $balance = $this->walletService->getBalance($request);
        return response()
            ->json(['balance' => $balance]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\NoWalletException
     */
    public function getTransactions(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'string', new IranMobileRule()]
        ]);

        $transactions = $this->walletService->getTransactions($request);
        return TransactionResource::collection($transactions);
    }
}
