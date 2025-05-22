<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\GiftCard;
use App\Models\Category;
use Carbon\Carbon;
use DB;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $connection = DB::getDriverName();

        $dateFormatFunction = $connection === 'sqlite' 
            ? "strftime('%m-%Y', created_at)" 
            : "to_char(created_at, 'MM-YYYY')";

        // Órdenes por mes (últimos 6 meses)
        $ordersPerMonth = Order::select(
                DB::raw("$dateFormatFunction as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $labels = $ordersPerMonth->pluck('month')->map(function($m){
            return Carbon::createFromFormat('m-Y', $m)->format('M Y');
        });

        $data = $ordersPerMonth->pluck('total');

        // Distribución de categorías por stock
        $categoryDistribution = GiftCard::select('categories.name as category', DB::raw('SUM(gift_cards.stock) as total_stock'))
            ->join('categories', 'categories.id', '=', 'gift_cards.id_category')
            ->groupBy('categories.name')
            ->orderByDesc('total_stock')
            ->get();

        $categoryLabels = $categoryDistribution->pluck('category')->toArray();
        $categoryData = $categoryDistribution->pluck('total_stock')->toArray();

        // Ventas por mes (últimos 6 meses)
        $salesPerMonth = DB::table('orders')
            ->select(
                DB::raw("$dateFormatFunction as month"),
                DB::raw('SUM(total_price) as total_sales')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $salesLabels = $salesPerMonth->pluck('month')->map(fn($m) => Carbon::createFromFormat('m-Y', $m)->format('M Y'));
        $salesData = $salesPerMonth->pluck('total_sales');

        // Totales generales
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalGiftCards = GiftCard::count(); 
        $totalGiftCardStock = GiftCard::sum('stock'); 
        $totalGiftCardStockValue = GiftCard::sum(DB::raw('stock * amount'));

        $totalSales = Order::sum('total_price');

        return view('dashboard.index', compact(
            'labels',            
            'data',            
            'salesLabels',       
            'salesData',          
            'totalUsers',
            'totalOrders',
            'totalGiftCards',
            'totalGiftCardStock',
            'totalGiftCardStockValue',
            'categoryLabels',
            'categoryData',
            'totalSales' 
        ));
    }


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
