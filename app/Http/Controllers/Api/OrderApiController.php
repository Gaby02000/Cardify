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
use App\Mail\GiftCardCodes;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Preference\Item;
use MercadoPago\Exceptions\MPApiException;

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

            // Creo que aca irian las preferencias de mercado pago, pero no estoy seguro

            // Configurar acceso
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
            $frontendUrl = env('FRONTEND_URL');
            $backendUrl = env('BACKEND_URL');
            // Log::debug('url del front: ' . $frontendUrl);

            // Crear preferencia
            $client = new PreferenceClient();

            // Crear los ítems
            $items = [];

            foreach ($order->orderItems as $orderItem) {
                $items[] = [
                    'title' => $orderItem->giftCard->title,
                    'quantity' => $orderItem->quantity,
                    'unit_price' => (float) $orderItem->price,
                ];
            }

            // $items = [
            //     [
            //         'title' => "Item Test",
            //         'quantity' => 2,
            //         'unit_price' => 300,
            //     ]
            // ];

            $payer = [
                "name" => $user->name,
                "email" => $user->email,
            ];

            // Log::debug('url del front para success: ' . $frontendUrl . '/order-confirmed');
            // Log::debug('url del front para failed: ' . $frontendUrl . '/order-failed');
            // Log::debug('url del front para pending: ' . $frontendUrl . '/order-confirmed');

            $back_urls = [
                "success" => $frontendUrl . "/order-confirmed",
                "failure" => $frontendUrl . "/order-failed",
                "pending" => $frontendUrl . "/order-confirmed",
            ];

            // $back_urls = [
            //     "success" => "https://cardify-frontend-git-dev-gaby02000s-projects.vercel.app/order-confirmed",
            //     "failure" => "https://cardify-frontend-git-dev-gaby02000s-projects.vercel.app/order-failed",
            //     "pending" => "https://cardify-frontend-git-dev-gaby02000s-projects.vercel.app/order-confirmed",
            // ];

            $request = [
                "items" => $items,
                "payer" => $payer,
                "back_urls" => $back_urls,
                "notification_url" => $backendUrl . '/apis/payment',
                "auto_return" => "approved",
                "external_reference" => 123456,
                "statement_descriptor" => 'Cardify'
            ];

            Log::debug('Request: ' . json_encode($request));

            // Crear preferencia
            $preference = $client->create($request);

            DB::commit();
            $codes = [];

            foreach ($order->orderItems as $item) {
                for ($i = 0; $i < $item->quantity; $i++) {
                    $codes[] = [
                        'gift_card' => $item->giftCard->title,
                        'code' => strtoupper(uniqid('GC-')),
                    ];
                }
            }

            Mail::to($user->email)->send(new GiftCardCodes($user, $codes));

            return response()->json([
                'message' => 'Orden creada correctamente',
                'order' => $order->load('orderItems.giftCard'),
                // 'preference_id' => $preference->id,
                'preference' => $preference,
            ], 201);
        } catch (MPApiException $e) {
            DB::rollBack();
            Log::error('Mercado Pago API ERROR (message): ' . $e->getMessage());
            Log::error('Mercado Pago API ERROR (response): ' . json_encode($e->getApiResponse()->getContent()));
            return response()->json(['message' => 'Error de Mercado Pago'], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error general: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno'], 500);
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