<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GiftCardApiController extends Controller
{
    public function index(Request $request)
    {
        // Solo las visibles: las ocultas por el admin no salen en la tienda.
        $query = GiftCard::with('category')->where('is_active', true);

        if ($category = $request->input('category')) {
            $query->where('id_category', $category);
        }

        if ($search = $request->input('search')) {
            $driver = DB::getDriverName();
            $query->where(function ($q) use ($search, $driver) {
                if ($driver === 'pgsql') {
                    $q->whereRaw('title ILIKE ?', ["%{$search}%"])
                        ->orWhereRaw('description ILIKE ?', ["%{$search}%"]);
                } else {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                }
            });
        }

        $sortField = $request->input('sort');
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';
        if (in_array($sortField, ['price', 'stock', 'amount', 'title', 'created_at'], true)) {
            $query->orderBy($sortField, $direction);
        } else {
            $query->orderBy('id', 'desc'); // novedades primero por defecto
        }

        // Tope alto: el frontend puede pedir todo el catálogo de una para
        // navegarlo y filtrarlo sin conexión.
        $perPage = max(1, min(1000, (int) $request->input('per_page', 10)));

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function show($id)
    {
        $giftcard = GiftCard::with('category')->where('is_active', true)->findOrFail($id);
        return response()->json($giftcard);
    }
}
