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

        // Rango de fechas: desde el 1 de enero hasta el 31 de diciembre del año actual
        $start = Carbon::now()->startOfYear();    // 1 de enero
        $end = Carbon::now()->endOfYear();        // 31 de diciembre

        $randomDate = function () use ($start, $end) {
            $timestamp = rand($start->timestamp, $end->timestamp);
            return Carbon::createFromTimestamp($timestamp);
        };

        $orders = [
            [
                'user_id' => 1,
                'cart_id' => 1,
                'total_price' => 245.00,
                'status' => 'pending',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 2,
                'cart_id' => 2,
                'total_price' => 272.50,
                'status' => 'completed',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 3,
                'cart_id' => 3,
                'total_price' => 155.00,
                'status' => 'shipped',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 4,
                'cart_id' => 4,
                'total_price' => 270.00,
                'status' => 'processing',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 5,
                'cart_id' => 5,
                'total_price' => 90.00,
                'status' => 'cancelled',
                'created_at' => $randomDate(),
            ],
            // Más órdenes para usuarios 1 a 5
            [
                'user_id' => 1,
                'cart_id' => 1,
                'total_price' => 180.00,
                'status' => 'completed',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 2,
                'cart_id' => 2,
                'total_price' => 210.00,
                'status' => 'pending',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 3,
                'cart_id' => 3,
                'total_price' => 130.00,
                'status' => 'cancelled',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 4,
                'cart_id' => 4,
                'total_price' => 300.00,
                'status' => 'shipped',
                'created_at' => $randomDate(),
            ],
            [
                'user_id' => 5,
                'cart_id' => 5,
                'total_price' => 75.00,
                'status' => 'processing',
                'created_at' => $randomDate(),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
