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
        $query = GiftCard::with('category');

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

        if ($request->has('sort')) {
            $sortField = $request->input('sort');
            $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';
            if (in_array($sortField, ['price', 'stock', 'amount'])) {
                $query->orderBy($sortField, $direction);
            }
        }

        return response()->json($query->paginate(10));
    }

    public function show($id)
    {
        $giftcard = GiftCard::with('category')->findOrFail($id);
        return response()->json($giftcard);
    }
}
