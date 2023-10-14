<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_login()
    {
        $user = User::factory()->create([
            'mobile' => '09120000000',
        ]);

        $userService = new UserService();

        $response = $this->postJson('/api/v1/login', ['mobile' => '09120000000']);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'type']);

        $user = $userService->getUserByMobile('09120000000');
        $this->assertNotEmpty($user->tokens);
    }

    public function test_user_login_with_invalid_mobile()
    {
        $response = $this->postJson('/api/v1/login', ['mobile' => 'invalid']);

        $response->assertJsonStructure(['message', 'data']);
    }
}
