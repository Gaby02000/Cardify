<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{

 public function show(Request $request)
    {
        $user = Auth::guard('user_client')->user();
        $userId = $user?->id;
        $sessionId = $request->session()->getId();

        $cart = null;

        if ($userId) {
            $cart = Cart::where('user_client_id', $userId)->first();

            if (!$cart) {
                // Si el usuario recién se logueó y tenía un carrito de sesión
                $guestCart = Cart::where('session_id', $sessionId)->first();
                if ($guestCart) {
                    $guestCart->user_client_id = $userId;
                    $guestCart->session_id = null;
                    $guestCart->save();
                    $cart = $guestCart;
                }
            }
        } else {
            $cart = Cart::where('session_id', $sessionId)->first();
        }

        if ($cart) {
            $cart->load('cartItems.giftCard');
        }

        return response()->json([
            'status' => 'success',
            'cart' => $cart,
            'user_id' => $userId,
            'session_id' => $sessionId,
        ]);
    }


    public function addItem(Request $request)
    {
        $request->validate([
            'gift_card_id' => 'required|exists:gift_cards,id',
            'quantity' => 'integer|min:1',
        ]);

        $user = Auth::guard('user_client')->user();
        $userId = $user?->id;
        $sessionId = $request->session()->getId();

        if ($userId) { // Buscar carrito existente del usuario o crear
            $cart = Cart::firstOrCreate(['user_client_id' => $userId]);
            if ($sessionId) {
                $guestCart = Cart::where('session_id', $sessionId)->first();
                if ($guestCart) {
                    foreach ($guestCart->cartItems as $guestItem) {
                        $item = CartItem::firstOrNew([
                            'cart_id' => $cart->id,
                            'gift_card_id' => $guestItem->gift_card_id,
                        ]);
                        $item->quantity = ($item->exists ? $item->quantity : 0) + $guestItem->quantity;
                        $item->save();
                    }
                    $guestCart->cartItems()->delete();
                    $guestCart->delete();
                }
            }
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'gift_card_id' => $request->gift_card_id,
        ]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + ($request->quantity ?? 1);
        $item->save();
        $item->load('giftCard');

        return response()->json([
            'data' => $item,
            'session_id' => $cart->session_id,
        ], 201);
    }


   public function clear(Request $request)
    {
        $userId = $request->user()?->id;
        $sessionId = $request->input('session_id');

        if ($userId) {
            $cart = Cart::where('user_id', $userId)->first();
        } elseif ($sessionId) {
            $cart = Cart::where('session_id', $sessionId)->first();
        } else {
            return response()->json(['error' => 'No cart found to clear'], 404);
        }

        if ($cart) {
            $cart->cartItems()->delete();
            return response()->json(['message' => 'Cart cleared'], 200);
        }

        return response()->json(['error' => 'No cart found to clear'], 404);
    }


    public function removeItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer|exists:cart_items,id',
            'session_id' => 'nullable|string',
        ]);

        $user = Auth::guard('user_client')->user();
        $userId = $user?->id;
        $sessionId = $request->input('session_id');

        if ($userId) {
            $cart = Cart::where('user_client_id', $userId)->first();
        } elseif ($sessionId) {
            $cart = Cart::where('session_id', $sessionId)->first();
        } else {
            return response()->json(['error' => 'No cart found'], 404);
        }

        if (!$cart) {
            return response()->json(['error' => 'No cart found'], 404);
        }

        $cartItem = $cart->cartItems()->where('id', $request->cart_item_id)->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Cart item not found in your cart'], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart',
            'cart_item_id' => $request->cart_item_id,
        ], 200);
    }
}
