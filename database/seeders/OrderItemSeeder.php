<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        $orderItems = [
            ['order_id' => 1, 'cart_item_id' => 1, 'gift_card_id' => 1, 'quantity' => 2, 'price' => 75.00],
            ['order_id' => 1, 'cart_item_id' => 2, 'gift_card_id' => 2, 'quantity' => 1, 'price' => 50.00],
            ['order_id' => 1, 'cart_item_id' => 3, 'gift_card_id' => 3, 'quantity' => 4, 'price' => 120.00],

            ['order_id' => 2, 'cart_item_id' => 4, 'gift_card_id' => 1, 'quantity' => 1, 'price' => 37.50],
            ['order_id' => 2, 'cart_item_id' => 5, 'gift_card_id' => 5, 'quantity' => 2, 'price' => 100.00],
            ['order_id' => 2, 'cart_item_id' => 6, 'gift_card_id' => 6, 'quantity' => 3, 'price' => 135.00],

            ['order_id' => 3, 'cart_item_id' => 7, 'gift_card_id' => 4, 'quantity' => 2, 'price' => 80.00],
            ['order_id' => 3, 'cart_item_id' => 8, 'gift_card_id' => 2, 'quantity' => 3, 'price' => 75.00],

            ['order_id' => 4, 'cart_item_id' => 9, 'gift_card_id' => 7, 'quantity' => 1, 'price' => 45.00],
            ['order_id' => 4, 'cart_item_id' => 10, 'gift_card_id' => 8, 'quantity' => 5, 'price' => 225.00],

            ['order_id' => 5, 'cart_item_id' => 11, 'gift_card_id' => 3, 'quantity' => 2, 'price' => 60.00],
            ['order_id' => 5, 'cart_item_id' => 12, 'gift_card_id' => 9, 'quantity' => 1, 'price' => 30.00],

            ['order_id' => 6, 'cart_item_id' => 13, 'gift_card_id' => 2, 'quantity' => 4, 'price' => 100.00],

            ['order_id' => 7, 'cart_item_id' => 14, 'gift_card_id' => 1, 'quantity' => 2, 'price' => 50.00],

            ['order_id' => 8, 'cart_item_id' => 15, 'gift_card_id' => 10, 'quantity' => 3, 'price' => 135.00],
        ];

        foreach ($orderItems as $item) {
            OrderItem::create($item);
        }
    }
}
