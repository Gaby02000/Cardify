<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemApiController extends Controller
{
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
}
