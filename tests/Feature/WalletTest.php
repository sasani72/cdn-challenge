<?php

namespace Tests\Feature;

use App\Exceptions\NoWalletException;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $response = $this->get('/api/v1/wallet/balance?mobile=09120000000');

        $response->assertStatus(200);
        $response->assertJsonStructure(['balance']);
        $response->assertJson(['balance' => 1500]);
    }

    public function test_get_transactions()
    {
        $user = User::factory()->create(['mobile' => '09120000000']);

        Wallet::factory()
            ->has(Transaction::factory()->count(5), 'transactions')
            ->create(['user_id' => $user->id]);


        $response = $this->get('/api/v1/wallet/transactions?mobile=09120000000');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_get_balance_no_wallet()
    {
        $response = $this->get('/api/v1/wallet/balance?mobile=09120000000');

        $response->assertJson(['message' => 'No wallet found for this user']);
    }

    public function test_get_transactions_no_wallet()
    {
        $response = $this->get('/api/v1/wallet/transactions?mobile=09120000000');

        $response->assertJson(['message' => 'No wallet found for this user']);
    }
}
