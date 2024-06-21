<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WithdrawalRequest;
use App\Models\User;
use Illuminate\Support\Testing\Fakes\Fake;

class WithdrawalRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of possible statuses
        $statuses = [
            WithdrawalRequest::STATUS_PENDING,
            WithdrawalRequest::STATUS_VERIFIED,
            WithdrawalRequest::STATUS_CANCELED,
            WithdrawalRequest::STATUS_FAILED,
            WithdrawalRequest::STATUS_SUCCEEDED,
        ];
        $start = strtotime('2024-01-01');
        $end = strtotime('2024-06-20');
        $randomTimestamp = rand($start, $end);
        $methods = ['bank account IBAN', 'paypal'];
        // Create 50 WithdrawalRequest records
        for ($i = 0; $i < 50; $i++) {
            WithdrawalRequest::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'amount' => rand(100, 10000),
                'method' => $methods[rand(0, 1)],
                'details' => 'Withdrawal details ' . $i,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => date('Y-m-d H:i:s', $randomTimestamp),

            ]);
        }
    }
}
