<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartItem;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        $cartItems = [
            // Cart 1
            ['cart_id' => 1, 'gift_card_id' => 1, 'quantity' => 2],
            ['cart_id' => 1, 'gift_card_id' => 2, 'quantity' => 1],
            ['cart_id' => 1, 'gift_card_id' => 3, 'quantity' => 4],

            // Cart 2
            ['cart_id' => 2, 'gift_card_id' => 1, 'quantity' => 1],
            ['cart_id' => 2, 'gift_card_id' => 5, 'quantity' => 2],
            ['cart_id' => 2, 'gift_card_id' => 6, 'quantity' => 3],

            // Cart 3
            ['cart_id' => 3, 'gift_card_id' => 4, 'quantity' => 2],
            ['cart_id' => 3, 'gift_card_id' => 2, 'quantity' => 3],

            // Cart 4
            ['cart_id' => 4, 'gift_card_id' => 7, 'quantity' => 1],
            ['cart_id' => 4, 'gift_card_id' => 8, 'quantity' => 5],

            // Cart 5
            ['cart_id' => 5, 'gift_card_id' => 3, 'quantity' => 2],
            ['cart_id' => 5, 'gift_card_id' => 9, 'quantity' => 1],

            // Cart 6
            ['cart_id' => 6, 'gift_card_id' => 2, 'quantity' => 4],

            // Cart 7
            ['cart_id' => 7, 'gift_card_id' => 1, 'quantity' => 2],

            // Cart 8
            ['cart_id' => 8, 'gift_card_id' => 10, 'quantity' => 3],

            // Cart 9
            ['cart_id' => 9, 'gift_card_id' => 5, 'quantity' => 1],

            // Cart 10
            ['cart_id' => 10, 'gift_card_id' => 3, 'quantity' => 2],

            // Cart 11
            ['cart_id' => 11, 'gift_card_id' => 4, 'quantity' => 1],

            // Cart 12
            ['cart_id' => 12, 'gift_card_id' => 6, 'quantity' => 5],

            // Cart 13
            ['cart_id' => 13, 'gift_card_id' => 2, 'quantity' => 3],

            // Cart 14
            ['cart_id' => 14, 'gift_card_id' => 7, 'quantity' => 1],

            // Cart 15
            ['cart_id' => 15, 'gift_card_id' => 1, 'quantity' => 2],
        ];

        foreach ($cartItems as $item) {
            CartItem::create($item);
        }
    }
}
