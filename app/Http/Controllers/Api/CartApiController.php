<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{
    /**
     * Mostrar el carrito actual del usuario autenticado.
     */
    public function show()
    {
        $user = Auth::user();
       
        $cart = Cart::with(['cartItems.giftCard'])->where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'El usuario no tiene un carrito activo.'], 404);
        }

        return response()->json([
            'cart_id' => $cart->id,
            'items' => $cart->cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'gift_card_id' => $item->gift_card_id,
                    'gift_card_name' => $item->giftCard->name ?? null,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->giftCard->price ?? 0,
                    'subtotal' => $item->quantity * ($item->giftCard->price ?? 0),
                ];
            }),
            'total_items' => $cart->cartItems->sum('quantity'),
            'total_price' => $cart->cartItems->sum(function ($item) {
                return $item->quantity * ($item->giftCard->price ?? 0);
            }),
        ]);
    }

    /**
     * Vaciar el carrito del usuario autenticado.
     */
    public function destroy()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'No hay carrito para eliminar.'], 404);
        }

        $cart->cartItems()->delete();

        return response()->json(['message' => 'Carrito vaciado.']);
    }
}
