<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\GiftCardCodes;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class OrderApiController extends Controller
{
    public function store(Request $request)
    {
        // Ruta protegida con auth:sanctum -> siempre hay usuario.
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Debes iniciar sesión para realizar una compra'], 401);
        }

        $cart = Cart::with('cartItems.giftCard')
            ->where('user_client_id', $user->id)
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Carrito vacío o no encontrado'], 400);
        }

        // Validar stock de TODOS los ítems antes de tocar la base.
        foreach ($cart->cartItems as $item) {
            if (!$item->giftCard) {
                return response()->json(['message' => 'Una giftcard del carrito ya no está disponible'], 422);
            }
            if ($item->giftCard->stock < $item->quantity) {
                return response()->json([
                    'error' => "No hay suficiente stock para '{$item->giftCard->title}'",
                    'gift_card_id' => $item->giftCard->id,
                    'stock_disponible' => $item->giftCard->stock,
                ], 422);
            }
        }

        $total = $cart->cartItems->sum(fn ($i) => $i->quantity * $i->giftCard->price);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_client_id' => $user->id,
                'cart_id' => $cart->id,
                'total_price' => $total,
                'status' => 'pendiente',
            ]);

            foreach ($cart->cartItems as $item) {
                $item->giftCard->decrement('stock', $item->quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'cart_item_id' => $item->id,
                    'gift_card_id' => $item->gift_card_id,
                    'quantity' => $item->quantity,
                    'price' => $item->giftCard->price,
                ]);
            }

            $order->load('orderItems.giftCard');

            $preference = $this->createMercadoPagoPreference($order, $user);

            DB::commit();
        } catch (MPApiException $e) {
            DB::rollBack();
            Log::error('Mercado Pago API ERROR: ' . $e->getMessage(), [
                'response' => optional($e->getApiResponse())->getContent(),
            ]);
            return response()->json(['message' => 'No se pudo generar el pago con Mercado Pago'], 502);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creando la orden: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno al crear la orden'], 500);
        }

        // --- Entrega de códigos (best-effort, fuera de la transacción) ---
        $codes = [];
        foreach ($order->orderItems as $orderItem) {
            for ($i = 0; $i < $orderItem->quantity; $i++) {
                $codes[] = [
                    'gift_card' => $orderItem->giftCard->title,
                    'code' => strtoupper(uniqid('GC-')),
                ];
            }
        }

        try {
            Mail::to($user->email)->send(new GiftCardCodes($user, $codes));
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar el email de códigos: ' . $e->getMessage());
        }

        // Vaciar el carrito ya convertido en orden.
        $cart->cartItems()->delete();

        return response()->json([
            'message' => 'Orden creada correctamente',
            'order' => $order,
            'preference_id' => $preference->id,
            'init_point' => $preference->init_point,
            'sandbox_init_point' => $preference->sandbox_init_point,
        ], 201);
    }

    /**
     * Crea la preferencia de Checkout Pro para la orden.
     */
    private function createMercadoPagoPreference(Order $order, $user)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $frontendUrl = rtrim((string) config('services.frontend_url'), '/');
        $backendUrl  = rtrim((string) config('services.backend_url'), '/');

        $data = [
            'items' => $order->orderItems->map(fn ($oi) => [
                'id' => (string) $oi->gift_card_id,
                'title' => $oi->giftCard->title,
                'quantity' => (int) $oi->quantity,
                'unit_price' => (float) $oi->price,
                'currency_id' => 'ARS',
            ])->all(),
            'payer' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'back_urls' => [
                'success' => $frontendUrl . '/order-confirmed',
                'failure' => $frontendUrl . '/order-failed',
                'pending' => $frontendUrl . '/order-confirmed',
            ],
            'external_reference' => (string) $order->id,
            'statement_descriptor' => 'Cardify',
        ];

        // Mercado Pago rechaza auto_return / notification_url con dominios locales.
        if (str_starts_with($frontendUrl, 'https://')) {
            $data['auto_return'] = 'approved';
        }
        if (str_starts_with($backendUrl, 'https://')) {
            $data['notification_url'] = $backendUrl . '/apis/payment';
        }

        return (new PreferenceClient())->create($data);
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        if ($order->user_client_id !== $user?->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $order->load('orderItems.giftCard');

        return response()->json($order);
    }
}
