<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $mobile
     * @return mixed
     */
    public function getUserByMobile($mobile)
    {
        $user = User::with('wallet')->firstOrCreate(['mobile' => $mobile]);

        if ($user->wasRecentlyCreated) {
            $user->wallet()->create();
        }
        return $user;
    }
}
