<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        OrderItem::create([
            'order_id' => 1,
            'cart_item_id' => 1,
            'gift_card_id' => 1,
            'quantity' => 2,
            'price' => 75.00,
        ]);

        OrderItem::create([
            'order_id' => 1,
            'cart_item_id' => 2,
            'gift_card_id' => 3,
            'quantity' => 1,
            'price' => 50.00,
        ]);

        OrderItem::create([
            'order_id' => 2,
            'cart_item_id' => 3,
            'gift_card_id' => 2,
            'quantity' => 3,
            'price' => 100.00,
        ]);
    }
}
