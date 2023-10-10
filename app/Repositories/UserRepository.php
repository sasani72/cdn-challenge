<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    /**
     * @param $id
     * @return mixed
     */
    public function getByMobile($mobile)
    {
        return User::firstOrCreate(['mobile' => $mobile]);
    }
}
