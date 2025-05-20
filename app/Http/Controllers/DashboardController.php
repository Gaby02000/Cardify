<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\GiftCard;
use Carbon\Carbon;
use DB;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordersPerMonth = Order::select(
                DB::raw("strftime('%m-%Y', created_at) as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $ordersPerMonth->pluck('month')->map(function($m){
            return Carbon::createFromFormat('m-Y', $m)->format('M Y');
        });

        $data = $ordersPerMonth->pluck('total');

        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalGiftCards = GiftCard::count(); 
        $totalGiftCardStock = GiftCard::sum('stock'); 

        $totalGiftCardStockValue = GiftCard::sum(DB::raw('stock * amount'));

        return view('dashboard.index', compact(
            'labels',
            'data',
            'totalUsers',
            'totalOrders',
            'totalGiftCards',
            'totalGiftCardStock',
            'totalGiftCardStockValue'
        ));
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
        //
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
