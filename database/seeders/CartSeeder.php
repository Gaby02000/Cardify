<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    public function run()
    {
        Cart::truncate();

        for ($i = 1; $i <= 15; $i++) {
            Cart::create([
                'user_client_id' => rand(1, 5), // 👈 usa user_client_id en vez de user_id
            ]);
        }
    }
}

