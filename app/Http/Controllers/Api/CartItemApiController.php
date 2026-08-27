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

        $user = $request->user('sanctum');

        if ($user) {
            $cart = Cart::firstOrCreate(['user_client_id' => $user->id]);
        } else {
            $sessionId = $request->input('session_id');
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

        $this->authorizeCartItem($request, $cartItem);

        $stock = $cartItem->giftCard?->stock ?? 0;
        if ($request->quantity > $stock) {
            return response()->json([
                'error' => 'No hay suficiente stock disponible.',
                'available_stock' => $stock,
            ], 422);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Cantidad actualizada',
            'data' => $cartItem->load('giftCard'),
        ], 200);
    }

   public function destroy(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($request, $cartItem);

        $cartItem->delete();

        return response()->json([
            'message' => 'Item eliminado del carrito'
        ]);
    }

    /**
     * El item pertenece al usuario autenticado (token) o al carrito de invitado
     * identificado por el `session_id` que envía el frontend.
     */
    private function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        $user = $request->user('sanctum');
        $cart = $cartItem->cart;

        $owns = $user
            ? $cart->user_client_id === $user->id
            : $cart->session_id !== null && $cart->session_id === $request->input('session_id');

        abort_unless($owns, 403, 'No autorizado');
    }

    public function index(Request $request)
    {
        $user = $request->user('sanctum');

        if ($user) {
            $cart = Cart::with('cartItems.giftCard')->where('user_client_id', $user->id)->first();
        } else {
            $sessionId = $request->input('session_id');
            $cart = Cart::with('cartItems.giftCard')->where('session_id', $sessionId)->first();
        }

        return response()->json([
            'cart' => $cart
        ]);
    }
}
