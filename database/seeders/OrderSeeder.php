<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define possible order statuses
        $statuses = [
            Order::STATU_UNPAID,
            Order::STATUS_IN_DELIVERY,
            Order::STATUS_RECEIVED,
            Order::STATU_PAID,
        ];

        // Generate and insert 50 orders
        for ($i = 0; $i < 50; $i++) {
            Order::create([
                'shipping_address' => [
                    'address' => 'Address ' . $i,
                    'city' => 'City ' . $i,
                    'country' => 'Country ' . $i,
                ],
                'user_id' => 1,  // Replace with your desired user_id
                'status' => $statuses[array_rand($statuses)],
                'total_amount' => rand(50, 500), // Random total amount
                'session_id' => 'session_' . $i, // Example session ID
            ]);
        }
    }
}
