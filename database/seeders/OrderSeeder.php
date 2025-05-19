<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Ajusta user_id y cart_id según datos existentes
        Order::create([
            'user_id' => 1,
            'cart_id' => 1,
            'total_price' => 150.00,
            'status' => 'pending',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        Order::create([
            'user_id' => 2,
            'cart_id' => 2,
            'total_price' => 300.50,
            'status' => 'completed',
            'created_at' => Carbon::now()->subDay(),
        ]);
    }
}
