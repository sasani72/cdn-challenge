<?php

namespace App\Jobs;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDeposit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $wallet;
    protected $amount;

    /**
     * Create a new job instance.
     */
    public function __construct($amount, Wallet $wallet)
    {
        $this->wallet = $wallet;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->wallet->balance += $this->amount;
        $this->wallet->save();

        Transaction::create([
            'wallet_id' => $this->wallet->id,
            'amount' => $this->amount,
            'type' => 'deposit',
        ]);
    }
}
