<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartItem;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        $cartItems = [
            [
                'cart_id' => 1,
                'gift_card_id' => 1,
                'quantity' => 2,
                'order_item_id' => null,
            ],
            [
                'cart_id' => 1,
                'gift_card_id' => 2,
                'quantity' => 1,
                'order_item_id' => null,
            ],
            [
                'cart_id' => 2,
                'gift_card_id' => 3,
                'quantity' => 3,
                'order_item_id' => null,
            ],
        ];

        foreach ($cartItems as $item) {
            CartItem::create($item);
        }
    }
}