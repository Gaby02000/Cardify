<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** Estados canónicos + variantes históricas equivalentes. */
    private const STATUS_GROUPS = [
        'pagado'      => ['pagado', 'completed', 'shipped', 'authorized'],
        'pendiente'   => ['pendiente', 'pending', 'processing', 'in_process'],
        'rechazado'   => ['rechazado', 'rejected', 'cancelled'],
        'reembolsado' => ['reembolsado', 'refunded', 'charged_back'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Filtro por estado
        if (($status = $request->input('status')) && isset(self::STATUS_GROUPS[$status])) {
            $query->whereIn('status', self::STATUS_GROUPS[$status]);
        }

        // Rango de fechas
        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Búsqueda por cliente
        if ($search = trim((string) $request->input('search'))) {
            $like = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->whereHas('user', fn ($q) => $q
                ->where('name', $like, "%{$search}%")
                ->orWhere('email', $like, "%{$search}%"));
        }

        // Ordenamiento
        $sortField = $request->input('sort');
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';
        if (in_array($sortField, ['id', 'total_price', 'status', 'created_at'], true)) {
            $query->orderBy($sortField, $direction);
        } else {
            $query->latest('created_at');
        }

        $orders = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('orders._table', compact('orders'))->render();
        }

        return view('orders.index', compact('orders'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.giftCard');

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
