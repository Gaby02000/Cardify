<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
    public function store(Request $req)
    {
        $cart = Cart::where('user_id', $req->user()->id)
                    ->with('cartItems.giftCard')
                    ->firstOrFail();

        return DB::transaction(function () use ($cart, $req) {
            $total = 0;
            foreach ($cart->cartItems as $ci) {
                $total += $ci->giftCard->price * $ci->quantity;
            }

            $order = Order::create([
                'user_id' => $cart->user_id,
                'cart_id' => $cart->id,
                'total_price' => $total,
                'status' => 'pending',
                'created_at' => now(),
            ]);

            foreach ($cart->cartItems as $ci) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'cart_item_id' => $ci->id,
                    'gift_card_id' => $ci->gift_card_id,
                    'quantity' => $ci->quantity,
                    'price' => $ci->giftCard->price
                ]);
            }

            // opcional: vaciar carrito
            $cart->cartItems()->delete();

            return response()->json($order->load('orderItems.giftCard'), 201);
        });
    }

    public function show(Request $req, Order $order)
    {
        $this->authorize('view', $order);

        $order->load('orderItems.giftCard');
        return response()->json($order);
    }
}
