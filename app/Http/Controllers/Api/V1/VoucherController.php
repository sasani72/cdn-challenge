<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\RedeemVoucher;
use App\Http\Requests\Voucher\StoreVoucher;
use App\Http\Resources\Voucher\VoucherResource;
use App\Http\Resources\Voucher\VoucherUsersResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Voucher;
use App\Services\UserService;
use App\Services\VoucherService;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    protected $voucherService;
    protected $userService;

    /**
     * VoucherController constructor.
     * @param VoucherService $voucherService
     * @param UserService $userService
     */
    public function __construct(VoucherService $voucherService, UserService $userService)
    {
        $this->voucherService = $voucherService;
        $this->userService = $userService;
    }

    /**
     * @param StoreVoucher $request
     * @return VoucherResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreVoucher $request): VoucherResource
    {
        $this->authorize('create', Voucher::class);

        $voucher = $this->voucherService->create($request->only((new Voucher)->getFillable()));
        return new VoucherResource($voucher);
    }

    /**
     * @param RedeemVoucher $request
     * @return mixed
     */
    public function redeem(RedeemVoucher $request)
    {
        $user = $this->userService->getUserByMobile($request->mobile);
        $this->voucherService->redeem($user, $request->code);

        return response()
            ->json(['message' => "Voucher redeemed successfully!"])
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param Voucher $voucher
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getVoucherUsers(Voucher $voucher)
    {
        return VoucherUsersResource::collection($voucher->users);
    }
}
