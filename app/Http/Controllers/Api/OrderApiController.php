<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderApiController extends Controller
{
    public function store(Request $request)
    {
        // Obtener usuario autenticado o session_id
        $user = Auth::guard('user_client')->user();
        $sessionId = $request->session()->getId();

        // Buscar carrito
        $cart = Cart::with('cartItems.giftCard')
            ->when($user, fn($q) => $q->where('user_client_id', $user->id))
            ->when(!$user, fn($q) => $q->where('session_id', $sessionId))
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Carrito vacío o no encontrado'], 400);
        }

        // Calcular total
        $total = 0;
        foreach ($cart->cartItems as $item) {
            $total += $item->quantity * $item->giftCard->price;
        }

        try {
            DB::beginTransaction();

            // Crear orden
            $order = Order::create([
                'user_client_id' => $user?->id,
                'cart_id' => $cart->id,
                'total_price' => $total,
                'status' => 'pendiente',
                'created_at' => Carbon::now(),
            ]);

            // Crear items de la orden
            foreach ($cart->cartItems as $item) {
                $giftCard = $item->giftCard;

                if ($giftCard->stock < $item->quantity) { //chequear stock
                    return response()->json([
                        'error' => "No hay suficiente stock para la giftcard '{$giftCard->title}'",
                        'gift_card_id' => $giftCard->id,
                        'stock_disponible' => $giftCard->stock,
                    ], 422);
                }
                
                // Restar stock 
                $giftCard->stock -= $item->quantity;
                $giftCard->save();
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'cart_item_id' => $item->id,
                    'gift_card_id' => $item->gift_card_id,
                    'quantity' => $item->quantity,
                    'price' => $item->giftCard->price,
                ]);
            }

            // Actualizar el total de la orden
            $order->total_price = $total;
            $order->save();

            // Eliminar los ítems del carrito
            $cart->cartItems()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Orden creada correctamente',
                'order' => $order->load('orderItems.giftCard'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear orden: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno al crear la orden'], 500);
        }
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
