<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * @param $mobile
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
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
