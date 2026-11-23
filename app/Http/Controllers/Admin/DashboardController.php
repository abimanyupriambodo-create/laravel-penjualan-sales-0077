<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'products_count' => Product::count(),
            'active_products_count' => Product::where('is_active', true)->count(),
            'low_stock_count' => Product::where('is_active', true)->where('stock', '<=', 5)->count(),
            'pending_orders_count' => Order::where('status', 'pending')->count(),
            'validated_orders_count' => Order::where('status', 'validated')->count(),
            'total_revenue' => (float) Order::where('status', 'validated')->sum('grand_total'),
        ];

        $recentOrders = Order::with('user')->latest('ordered_at')->take(5)->get();
        $recentMovements = StockMovement::with(['product', 'user'])->latest('movement_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentMovements'));
    }
}