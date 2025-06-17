<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartItemApiController extends Controller
{
   public function storeOrCreateCart(Request $request)
    {
        $request->validate([
            'gift_card_id' => 'required|exists:gift_cards,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::guard('user_client')->user();

        if ($user) {
            $cart = Cart::firstOrCreate(['user_client_id' => $user->id]);
        } else {
            $sessionId = $request->session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('gift_card_id', $request->gift_card_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            $existingItem = CartItem::create([
                'cart_id' => $cart->id,
                'gift_card_id' => $request->gift_card_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'message' => 'Item agregado al carrito',
            'data' => $existingItem
        ], 201);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Cantidad actualizada',
            'data' => $cartItem, 200
        ]);
    }

   public function destroy(Request $request, CartItem $cartItem)
    {
        $user = Auth::guard('user_client')->user();
        $sessionId = $request->session()->getId();

        $cart = $cartItem->cart;

        if ($user) {
            if ($cart->user_client_id !== $user->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        } else {
            if ($cart->session_id !== $sessionId) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item eliminado del carrito'
        ]);
    }

    public function index(Request $request)
    {
        $user = Auth::guard('user_client')->user();

        if ($user) {
            $cart = Cart::with('cartItems.giftCard')->where('user_client_id', $user->id)->first();
        } else {
            $sessionId = $request->session()->getId();
            $cart = Cart::with('cartItems.giftCard')->where('session_id', $sessionId)->first();
        }

        return response()->json([
            'cart' => $cart
        ]);
    }
}
