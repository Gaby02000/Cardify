<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderApiController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::guard('user_client')->user();
        $userId = $user?->id;

        if (!$userId) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // Obtener el carrito con sus items y giftCards relacionados
        $cart = Cart::where('user_id', $userId)
            ->with('cartItems.giftCard')
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Carrito vacío o no encontrado'], 400);
        }

        $order = DB::transaction(function () use ($cart, $userId) {
            $total = 0;
            foreach ($cart->cartItems as $item) {
                $total += $item->giftCard->price * $item->quantity;
            }

            // Crear la orden
            $order = Order::create([
                'user_client_id' => $userId,
                'cart_id'        => $cart->id,
                'total_price'    => $total,
                'status'         => 'paid',
                'created_at'     => now(),
            ]);

            // Crear orderItems asociados a la orden usando la relación
            foreach ($cart->cartItems as $item) {
                $order->orderItems()->create([
                    'cart_item_id' => $item->id,
                    'gift_card_id' => $item->gift_card_id,
                    'quantity'     => $item->quantity,
                    'price'        => $item->giftCard->price,
                ]);
            }

            // Vaciar el carrito
            $cart->cartItems()->delete();

            return $order;
        });

        // Recargar la orden con sus orderItems y giftCards relacionados
        $order = $order->fresh('orderItems.giftCard');

       return response()->json([
            'message' => 'Orden creada y pagada exitosamente',
            'order'   => [
                'id' => $order->id,
                'total_price' => $order->total_price,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'gift_card' => [
                            'id' => $item->giftCard->id,
                            'title' => $item->giftCard->title,
                            'price' => $item->giftCard->price,
                            // ...otros campos si querés
                        ]
                    ];
                })
            ]
        ], 201);
        }



    public function show(Request $request, Order $order)
    {
        $user = Auth::guard('user_client')->user();

        if ($order->user_client_id !== $user?->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $order->load('orderItems.giftCard');

        return response()->json($order);
    }
}
