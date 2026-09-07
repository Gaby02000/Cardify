<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user('sanctum');
        $userId = $user?->id;
        $sessionId = $request->input('session_id');

        $cart = null;

        if ($userId) {
            $cart = Cart::where('user_client_id', $userId)->first();

            if (!$cart && $sessionId) {
                // Si el usuario recién se logueó y tenía un carrito de invitado
                $guestCart = Cart::where('session_id', $sessionId)->first();
                if ($guestCart) {
                    $guestCart->user_client_id = $userId;
                    $guestCart->session_id = null;
                    $guestCart->save();
                    $cart = $guestCart;
                }
            }
        } elseif ($sessionId) {
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

        $user = $request->user('sanctum');
        $userId = $user?->id;
        $sessionId = $request->input('session_id');

        if (!$userId && !$sessionId) {
            return response()->json(['error' => 'session_id requerido para el carrito de invitado'], 422);
        }

        $quantityToAdd = $request->quantity ?? 1;

        $giftCard = GiftCard::find($request->gift_card_id);

        if ($quantityToAdd > $giftCard->stock) { //chequear stock
            return response()->json([
                'error' => 'No hay suficiente stock disponible.',
                'available_stock' => $giftCard->stock,
            ], 422);
        }

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
        $userId = $request->user('sanctum')?->id;
        $sessionId = $request->input('session_id');

        if ($userId) {
            $cart = Cart::where('user_client_id', $userId)->first();
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
}
