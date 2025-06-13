<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::truncate();

        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear();

        $randomDate = function () use ($start, $end) {
            $timestamp = rand($start->timestamp, $end->timestamp);
            return Carbon::createFromTimestamp($timestamp);
        };

        $orders = [
            [
                'user_client_id' => 1,
                'cart_id' => 1,
                'total_price' => 710.00,
                'status' => 'pending',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 2,
                'cart_id' => 2,
                'total_price' => 542.50,
                'status' => 'completed',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 3,
                'cart_id' => 3,
                'total_price' => 315.00,
                'status' => 'shipped',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 4,
                'cart_id' => 4,
                'total_price' => 1170.00,
                'status' => 'processing',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 5,
                'cart_id' => 5,
                'total_price' => 150.00,
                'status' => 'cancelled',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 1,
                'cart_id' => 1,
                'total_price' => 400.00,
                'status' => 'completed',
                'created_at' => $randomDate(),
            ],
            [ 
                'user_client_id' => 2,
                'cart_id' => 2,
                'total_price' => 100.00,
                'status' => 'pending',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 3,
                'cart_id' => 3,
                'total_price' => 405.00,
                'status' => 'cancelled',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 4,
                'cart_id' => 4,
                'total_price' => 0.00,
                'status' => 'shipped',
                'created_at' => $randomDate(),
            ],
            [
                'user_client_id' => 5,
                'cart_id' => 5,
                'total_price' => 0.00,
                'status' => 'processing',
                'created_at' => $randomDate(),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
