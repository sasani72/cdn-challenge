<?php

namespace Tests\Unit;

use App\Exceptions\InvalidVoucherException;
use App\Exceptions\RedeemedVoucherException;
use App\Models\User;
use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_voucher_valid()
    {
        $data = [
            'title' => 'Test Voucher',
            'code' => 'TEST123',
            'type' => 'charge',
            'amount' => 100,
            'max_uses' => 10,
            'starts_at' => now(),
            'expires_at' => now()->addDays(1),
        ];

        $voucherService = new VoucherService();
        $voucher = $voucherService->create($data);

        $this->assertInstanceOf(Voucher::class, $voucher);
    }

    public function test_redeem_valid_voucher()
    {
        $user = User::factory()->create();
        $user->wallet()->create();
        $voucher = Voucher::factory()->create(['max_uses' => 1, 'starts_at' => now()]);

        $voucherService = new VoucherService();
        $voucherService->redeem($user, $voucher->code);

        $this->assertDatabaseHas('user_voucher', [
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
        ]);
    }

    public function test_redeem_invalid_voucher_code()
    {
        $user = User::factory()->create();
        $user->wallet()->create();
        $invalidCode = 'INVALID123';

        $voucherService = new VoucherService();

        $this->expectException(InvalidVoucherException::class);
        $voucherService->redeem($user, $invalidCode);
    }

    public function test_redeem_invalid_voucher_date()
    {
        $user = User::factory()->create();
        $user->wallet()->create();
        $voucher = Voucher::factory()->create(['max_uses' => 1, 'starts_at' => now()->addDays(1)]);

        $voucherService = new VoucherService();

        $this->expectException(InvalidVoucherException::class);
        $voucherService->redeem($user, $voucher->code);
    }

    public function test_redeem_duplicate_voucher()
    {
        $user = User::factory()->create();
        $user->wallet()->create();
        $voucher = Voucher::factory()->create(['max_uses' => 1, 'starts_at' => now()]);
        $user->vouchers()->attach($voucher);

        $voucherService = new VoucherService();

        $this->expectException(RedeemedVoucherException::class);
        $voucherService->redeem($user, $voucher->code);
    }
}
