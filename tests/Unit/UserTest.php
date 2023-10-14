<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Wallet;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_get_user_by_mobile_creates_user_with_wallet()
    {
        $userService = new UserService();
        $mobile = '09120000000';

        $user = $userService->getUserByMobile($mobile);

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->wasRecentlyCreated);
        $this->assertInstanceOf(Wallet::class, $user->wallet);
    }
}
