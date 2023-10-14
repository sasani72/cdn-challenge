<?php

namespace Tests\Feature;

use App\Jobs\ProcessDeposit;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_voucher()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $voucherData = [
            'title' => 'Test Voucher',
            'code' => 'TEST123',
            'type' => 'charge',
            'amount' => 100000,
            'max_uses' => 100,
            'starts_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'expires_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user)->postJson('/api/v1/vouchers', $voucherData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('vouchers', $voucherData);
    }

    public function test_store_voucher_with_invalid_data()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $invalidVoucherData = [
            'code' => 'TEST123',
            'type' => 'example',
            'amount' => 100,
            'max_uses' => 10,
            'starts_at' => now(),
            'expires_at' => now()->addDays(10),
        ];

        $response = $this->actingAs($user)->postJson('/api/v1/vouchers', $invalidVoucherData);

        $response->assertJsonStructure(['message', 'data']);
    }

    public function test_store_voucher_with_unauthenticated_user()
    {
        $voucherData = [
            'title' => 'Test Voucher',
            'code' => 'TEST123',
            'type' => 'charge',
            'amount' => 100000,
            'max_uses' => 100,
            'starts_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'expires_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/v1/vouchers', $voucherData);
        $response->assertStatus(403);
    }

    public function test_voucher_redemption()
    {
        Queue::fake();

        $voucher = Voucher::factory()->create([
            'max_uses' => 1,
            'starts_at' => now()->subDays(1)->format('Y-m-d H:i:s')
        ]);

        $response = $this->postJson(
            '/api/v1/vouchers/redeem',
            [
                'code' => $voucher->code,
                'mobile' => '09120000000'
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Voucher redeemed successfully!']);

        $this->assertDatabaseHas('users', [
            'mobile' => '09120000000',
        ]);

        $user = User::where('mobile', '09120000000')->with('wallet')->first();


        $this->assertDatabaseHas('user_voucher', [
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
        ]);

        Queue::assertPushed(ProcessDeposit::class);
    }

    public function test_invalid_voucher_redemption()
    {
        $invalidCode = 'INVALID123';

        $response = $this->postJson('/api/v1/vouchers/redeem', [
            'code' => $invalidCode, 'mobile' => '09120000000'
        ]);

        $response->assertJsonStructure(['message', 'data']);
    }

    public function test_duplicate_voucher_redemption()
    {
        $user = User::factory()->create(['mobile' => '09120000000']);
        $voucher = Voucher::factory()->create(['max_uses' => 1, 'starts_at' => now()->subDays(1)->format('Y-m-d H:i:s')]);
        $user->vouchers()->attach($voucher);

        $response = $this->postJson('/api/v1/vouchers/redeem', ['code' => $voucher->code, 'mobile' => '09120000000']);

        $response->assertStatus(422);
        $this->assertDatabaseHas('user_voucher', [
            'user_id' => $user->id,
            'voucher_id' => $voucher->id
        ]);
    }
}
