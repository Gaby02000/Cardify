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

class OrderApiController extends Controller
{
  public function store()
    {
        $user = Auth::guard('user_client')->user();
        $userId = $user?->id;

        if (!$userId) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $cart = Cart::with('cartItems.giftCard')
            ->where('user_client_id', $userId)
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Carrito vacío o no encontrado'], 400);
        }

        try {
            DB::beginTransaction();

            $total = 0;
            $now = now();

            if ($cart->cartItems->isEmpty()) {
                return response()->json(['error' => 'Carrito vacío'], 400);
            }

            // Crear la orden primero (sin orderItems aún)
            $order = Order::create([
                'user_client_id' => $userId,
                'cart_id'        => $cart->id,
                'total_price'    => 0, // lo actualizamos después
                'status'         => 'paid',
                'created_at'     => $now,
            ]);

            $itemsCreados = [];

            $cartItems = $cart->cartItems()->with('giftCard')->get();

            foreach ($cartItems as $item) {
                $giftCard = $item->giftCard;

                if (!$giftCard) {
                    dump("❌ GiftCard no encontrada para cart_item_id {$item->id}");
                    continue;
                }

                if ($giftCard->stock < $item->quantity) { //chequear stock
                    DB::rollBack();
                    return response()->json([
                        'error' => "No hay suficiente stock para la giftcard '{$giftCard->title}'",
                        'gift_card_id' => $giftCard->id,
                        'stock_disponible' => $giftCard->stock,
                    ], 422);
                }
                // Restar stock 
                $giftCard->stock -= $item->quantity;
                $giftCard->save();

                $subtotal = $giftCard->price * $item->quantity;
                $total += $subtotal;

                $orderItem = new OrderItem([
                    'cart_item_id' => $item->id,
                    'gift_card_id' => $giftCard->id,
                    'quantity'     => $item->quantity,
                    'price'        => $giftCard->price,
                ]);

                $order->orderItems()->save($orderItem);
                dump('✅ OrderItem creado:', $orderItem->toArray());
                $itemsCreados[] = $orderItem;
            }

            // Si no se creó ningún item, no tiene sentido continuar
            if (empty($itemsCreados)) {
                DB::rollBack();
                return response()->json(['error' => 'No se pudo guardar ningún OrderItem.'], 422);
            }

            // Actualizar el total de la orden
            $order->total_price = $total;
            $order->save();

            // Eliminar los ítems del carrito
            $cart->cartItems()->delete();

            DB::commit();
        $order->load('orderItems.giftCard');

        // Verificamos qué trae orderItems:
        dump($order->orderItems->toArray());  // ¿Esto muestra los items?

        // Si está vacío, probá también:
        $itemsCount = $order->orderItems()->count();
        dump("OrderItems count: $itemsCount");

        return response()->json([
            'message' => 'Orden creada y pagada exitosamente',
            'order' => [
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
                        ],
                    ];
                }),
            ],
        ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear la orden: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear la orden',
                'error'   => $e->getMessage(),
            ], 500);
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
