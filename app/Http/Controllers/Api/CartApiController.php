<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function show(Request $req)
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => $req->user()->id]
        );
        $cart->load('cartItems.giftCard');
        return response()->json($cart);
    }

    public function addItem(Request $req)
    {
        $req->validate([
            'gift_card_id' => 'required|exists:gift_cards,id',
            'quantity' => 'integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => $req->user()->id]
        );

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'gift_card_id' => $req->gift_card_id,
        ]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + ($req->quantity ?? 1);
        $item->save();
        $item->load('giftCard');

        return response()->json($item, 201);
    }

    public function removeItem(Request $req, CartItem $item)
    {
        $this->authorize('modify', $item);

        $item->delete();
        return response()->json(['message' => 'Item removed']);
    }

    public function clear(Request $req)
    {
        $cart = Cart::where('user_id', $req->user()->id)->first();
        if ($cart) {
            $cart->cartItems()->delete();
        }
        return response()->json(['message' => 'Cart cleared']);
    }
}
