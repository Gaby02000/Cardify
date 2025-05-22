<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 15; $i++) {
            Cart::create([
                'user_id' => rand(1, 5),  // Número aleatorio entre 1 y 5
                'order_id' => null,       // Carro sin orden aún
            ]);
        }
    }
}
