<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // GET /api/orders/my
    public function myOrders(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)
            ->with('items')          // include order items
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }
}
