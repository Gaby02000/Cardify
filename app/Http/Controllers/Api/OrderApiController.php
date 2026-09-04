<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Services\OrderPaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class OrderApiController extends Controller
{
    /**
     * Estados que representan una compra concretada. En "Mis compras" solo se
     * muestran estas: las órdenes pendientes (checkout no finalizado) o
     * rechazadas no aparecen en el historial del cliente.
     */
    private const VISIBLE_STATUSES = [
        'pagado', 'completed', 'shipped', 'authorized',
        'reembolsado', 'refunded', 'charged_back',
    ];

    /**
     * Crea la orden en estado "pendiente" y devuelve el link de pago.
     * NO se descuenta stock ni se entregan códigos acá: eso ocurre recién
     * cuando Mercado Pago confirma el pago (ver confirm() y el webhook).
     */
    public function store(Request $request)
    {
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

        // Solo validamos disponibilidad; el descuento real es al confirmar el pago.
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

        // Se cobra el precio ya con el descuento activo de cada gift card.
        $total = $cart->cartItems->sum(fn ($i) => $i->quantity * (float) $i->giftCard->final_price);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_client_id' => $user->id,
                'cart_id' => $cart->id,
                'total_price' => $total,
                'status' => 'pendiente',
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'cart_item_id' => $item->id,
                    'gift_card_id' => $item->gift_card_id,
                    'quantity' => $item->quantity,
                    'price' => $item->giftCard->final_price,
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

        return response()->json([
            'message' => 'Orden creada',
            'order' => $order,
            'preference_id' => $preference->id,
            'init_point' => $preference->init_point,
            'sandbox_init_point' => $preference->sandbox_init_point,
        ], 201);
    }

    /**
     * Lo llama el frontend al volver de Mercado Pago (back_urls). Verifica el
     * pago contra la API de MP y, si está aprobado, entrega los códigos.
     * Idempotente: si el webhook ya lo procesó, solo devuelve el resultado.
     */
    public function confirm(Request $request, Order $order, OrderPaymentService $payments)
    {
        if ($order->user_client_id !== $request->user()?->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $paymentId = $request->input('payment_id')
            ?? $request->input('collection_id')
            ?? $request->query('payment_id')
            ?? $request->query('collection_id');

        if ($paymentId) {
            $payments->syncFromPayment($paymentId);
            $order->refresh();
        }

        $order->load('orderItems.giftCard');

        return response()->json([
            'status' => $order->status, // pendiente | pagado | rechazado | reembolsado
            'codes'  => $order->status === 'pagado' ? ($order->codes ?? []) : [],
            'order'  => $order,
        ]);
    }

    /**
     * Historial de compras del usuario autenticado, con filtros, orden y
     * paginación. Se numera por usuario (1..N según el orden de compra);
     * nunca se expone el id real de la orden.
     *
     * Query params: page, per_page, status, date_from, date_to, search,
     * sort (fecha|total), direction (asc|desc).
     */
    public function index(Request $request)
    {
        $base = Order::where('user_client_id', $request->user()->id)
            ->whereIn('status', self::VISIBLE_STATUSES);

        // Número correlativo por usuario: mapa id => posición cronológica.
        $numberById = (clone $base)
            ->orderBy('created_at')->orderBy('id')
            ->pluck('id')
            ->flip()
            ->map(fn ($pos) => $pos + 1);

        $query = (clone $base)->with('orderItems.giftCard');

        // --- Filtro por estado (agrupa variantes históricas) ---
        $statusGroups = [
            'pagado'      => ['pagado', 'completed', 'shipped', 'authorized'],
            'pendiente'   => ['pendiente', 'pending', 'processing', 'in_process'],
            'rechazado'   => ['rechazado', 'rejected', 'cancelled'],
            'reembolsado' => ['reembolsado', 'refunded', 'charged_back'],
        ];
        if ($request->filled('status') && isset($statusGroups[$request->input('status')])) {
            $query->whereIn('status', $statusGroups[$request->input('status')]);
        }

        // --- Filtro por rango de fechas ---
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // --- Filtro por gift card ---
        if ($request->filled('search')) {
            $term = mb_strtolower(trim($request->input('search')));
            $query->whereHas('orderItems.giftCard', fn ($q) =>
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$term}%"]));
        }

        // --- Orden ---
        $sortCol = $request->input('sort') === 'total' ? 'total_price' : 'created_at';
        $dir = $request->input('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortCol, $dir)->orderBy('id', $dir);

        // --- Paginación ---
        // Tope alto: el frontend puede pedir todo el historial de una para
        // manipularlo sin conexión.
        $perPage = max(1, min(1000, (int) $request->input('per_page', 10)));
        $paginator = $query->paginate($perPage)->withQueryString();

        $paginator->getCollection()->transform(fn (Order $order) => [
            'number' => $numberById[$order->id] ?? null,
            'status' => $order->status,
            'total_price' => $order->total_price,
            'created_at' => $order->created_at,
            'codes' => $order->status === 'pagado' ? ($order->codes ?? []) : [],
            'items' => $order->orderItems->map(fn ($oi) => [
                'title' => $oi->giftCard->title ?? 'Gift card',
                'image' => $oi->giftCard->image ?? null,
                'quantity' => $oi->quantity,
                'price' => $oi->price,
                'line_total' => round((float) $oi->price * $oi->quantity, 2),
            ])->values(),
        ]);

        return response()->json($paginator);
    }

    /**
     * Recibo en PDF de una compra del usuario autenticado. Se pide por el
     * número correlativo (1..N), nunca por el id real de la orden.
     */
    public function receipt(Request $request, int $number)
    {
        $ids = Order::where('user_client_id', $request->user()->id)
            ->whereIn('status', self::VISIBLE_STATUSES)
            ->orderBy('created_at')->orderBy('id')
            ->pluck('id');

        $orderId = $ids[$number - 1] ?? null;
        abort_if(! $orderId, 404, 'Compra no encontrada');

        $order = Order::with(['orderItems.giftCard', 'user'])->findOrFail($orderId);

        $pdf = Pdf::loadView('pdf.receipt', [
            'order' => $order,
            'number' => $number,
        ])->setPaper('a4');

        return $pdf->download("recibo-cardify-{$number}.pdf");
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

        // Con auto_return, al aprobarse el pago MP redirige solo a back_urls.success.
        // MP lo rechaza si esa URL no es pública (localhost) -> reintentamos sin él.
        $data['auto_return'] = 'approved';

        if (str_starts_with($backendUrl, 'https://')) {
            $data['notification_url'] = $backendUrl . '/apis/payment';
        }

        $client = new PreferenceClient();

        try {
            return $client->create($data);
        } catch (MPApiException $e) {
            Log::warning('MP: preferencia sin auto_return (' . $e->getMessage() . ')');
            unset($data['auto_return']);
            return $client->create($data);
        }
    }
}
