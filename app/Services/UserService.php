<?php

namespace App\Services;

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
        return $this->userRepository->getByMobile($mobile);
    }
}
