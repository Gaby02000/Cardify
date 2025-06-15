<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemApiController extends Controller
{
    public function storeOrCreateCart(Request $request)
    {
        $request->validate([
            'gift_card_id' => 'required|exists:gift_cards,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Obtener o crear carrito del usuario autenticado
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado', $user], 401);
        }
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Verificar si ya existe ese ítem en el carrito
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
            'data' => $cartItem
        ]);
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();

        return response()->json([
            'message' => 'Item eliminado del carrito'
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        $cart = Cart::with('cartItems.giftCard')->where('user_id', $user->id)->first();

        return response()->json([
            'cart' => $cart
        ]);
    }
}
