<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userService->getUserByMobile($request->mobile);

        if ($user) {
            return [
                'token' => $user->createToken('Api')->plainTextToken,
                'type'  => 'Bearer'
            ];
        }
        throw new AuthenticationException();
    }
}
