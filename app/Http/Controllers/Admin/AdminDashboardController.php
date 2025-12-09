<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalSales     = Order::where('status', 'delivered')->sum('total_price');
        $totalOrders    = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts  = Product::count();

        $salesData = Order::selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, SUM(total_price) as sales')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at) asc')
            ->limit(6)
            ->get()
            ->map(fn ($row) => ['month' => $row->month, 'sales' => (float) $row->sales]);

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function (Order $order) {
                return [
                    '_id'          => $order->id,
                    'customerName' => optional($order->user)->name ?? 'Guest',
                    'createdAt'    => $order->created_at,
                    'total'        => (float) $order->total_price,
                    'status'       => ucfirst($order->status),
                ];
            });

        return view('admin.dashboard.index', [
            'totalSales'          => (float) $totalSales,
            'totalOrders'         => $totalOrders,
            'totalCustomers'      => $totalCustomers,
            'totalProducts'       => $totalProducts,
            'salesData'           => $salesData,
            'recentOrders'        => $recentOrders,
            'salesGrowthPercent'  => 0,
        ]);
    }
}
