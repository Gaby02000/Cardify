<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    public function run()
    {
        Cart::create([
            'user_id' => 1,
            'order_id' => null,  // Aún sin orden
        ]);

        Cart::create([
            'user_id' => 2,
            'order_id' => null,
        ]);
    }
}