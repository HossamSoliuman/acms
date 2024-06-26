<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
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

        // Define the date range
        $start = strtotime('2024-01-01');
        $end = strtotime('2024-06-20');

        for ($i = 0; $i < 50; $i++) {
            // Generate a random timestamp between the start and end dates
            $randomTimestamp = rand($start, $end);

            $order = Order::create([
                'shipping_address' => [
                    'address' => 'Address ' . $i,
                    'city' => 'City ' . $i,
                    'country' => 'Country ' . $i,
                ],
                'user_id' => rand(5, 50),
                'status' => $statuses[array_rand($statuses)],
                'total_amount' => rand(50, 500),
                'session_id' => 'session_' . $i,
                'created_at' => date('Y-m-d H:i:s', $randomTimestamp),
            ]);

            // Create random order items for each order
            for ($j = 0; $j < rand(1, 5); $j++) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => Product::inRandomOrder()->first()->id,
                    'quantity' => rand(1, 2),
                ]);
            }
        }
    }
}
