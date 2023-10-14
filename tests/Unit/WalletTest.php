<?php

namespace Tests\Unit;

use App\Exceptions\NoWalletException;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_balance()
    {
        $user = User::factory()->create(['mobile' => '09120000000']);

        $user->wallet()->create();
        $user->wallet->balance += 1500;
        $user->wallet->save();

        $walletService = new WalletService();
        $request = new \Illuminate\Http\Request();
        $request->merge(['mobile' => '09120000000']);

        $balance = $walletService->getBalance($request);

        $this->assertEquals(1500, $balance);
    }

    public function test_get_transactions()
    {
        $user = User::factory()->create(['mobile' => '09120000000']);

        $wallet = Wallet::factory()
            ->has(Transaction::factory()->count(5), 'transactions')
            ->create(['user_id' => $user->id]);

        $walletService = new WalletService();
        $request = new \Illuminate\Http\Request();
        $request->merge(['mobile' => '09120000000']);

        $result = $walletService->getTransactions($request);

        $this->assertEquals($wallet->transactions->count(), count($result));
    }

    public function test_get_balance_no_wallet()
    {
        $walletService = new WalletService();
        $request = new \Illuminate\Http\Request();
        $request->merge(['mobile' => 'non_existent_mobile']);

        $this->expectException(NoWalletException::class);
        $walletService->getBalance($request);
    }

    public function test_get_transactions_no_wallet()
    {
        $walletService = new WalletService();
        $request = new \Illuminate\Http\Request();
        $request->merge(['mobile' => 'non_existent_mobile']);

        $this->expectException(NoWalletException::class);
        $walletService->getTransactions($request);
    }
}
