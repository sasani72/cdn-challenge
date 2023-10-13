<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\RedeemVoucher;
use App\Http\Requests\Voucher\StoreVoucher;
use App\Http\Resources\Voucher\VoucherResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Voucher;
use App\Services\UserService;
use App\Services\VoucherService;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    protected $voucherService;
    protected $userService;

    public function __construct(VoucherService $voucherService, UserService $userService)
    {
        $this->voucherService = $voucherService;
        $this->userService = $userService;
    }

    /**
     * Store a newly created resource.
     *
     * @param StoreVoucher $request
     * @return VoucherResource
     */
    public function store(StoreVoucher $request): VoucherResource
    {
        $this->authorize('create', Voucher::class);

        $voucher = $this->voucherService->create($request->only((new Voucher)->getFillable()));
        return new VoucherResource($voucher);
    }

    /**
     * redeem voucher code
     * @param RedeemVoucher $request
     * @return Response
     */
    public function redeem(RedeemVoucher $request): Response
    {
        $user = $this->userService->getUserByMobile($request->mobile);
        $this->voucherService->redeem($user, $request->code);

        return response()
            ->json(['message' => "Voucher redeemed successfully!"])
            ->setStatusCode(Response::HTTP_OK);
    }
}
