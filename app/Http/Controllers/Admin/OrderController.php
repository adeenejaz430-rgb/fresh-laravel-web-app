<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $status = $request->get('status');

        $query = Order::query()->with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Distinct statuses for filter
        $statuses = Order::select('status')
            ->distinct()
            ->pluck('status')
            ->filter()
            ->values();

        return view('admin.orders.index', compact('orders', 'statuses', 'search', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $order->status = $data['status'];

        if ($data['status'] === 'delivered' && ! $order->delivered_at) {
            $order->delivered_at = now();
        }

        $order->save();

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated.');
    }
}
