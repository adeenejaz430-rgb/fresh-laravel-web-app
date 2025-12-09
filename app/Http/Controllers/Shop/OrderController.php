<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Show all orders for logged-in user.
     */
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    /**
     * Show single order details.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order); // optional policy

        $order->load('items.product');

        return view('shop.orders.show', compact('order'));
    }

    /**
     * Show checkout page.
     */
    public function create()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        $totalItems = array_sum(array_column($cart, 'quantity'));
        $totalPrice = array_reduce($cart, function ($carry, $item) {
            return $carry + $item['price'] * $item['quantity'];
        }, 0);

        return view('shop.checkout.index', compact('cart', 'totalItems', 'totalPrice'));
    }

    /**
     * Place order from cart.
     * This matches your Next.js flow where the cart already holds IDs/prices.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:50',
            'shipping_address' => 'required|string|max:500',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $totalPrice = array_reduce($cart, function ($carry, $item) {
            return $carry + $item['price'] * $item['quantity'];
        }, 0);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'          => $user->id,
                'status'           => 'pending',       // pending / paid / shipped / completed
                'payment_status'   => 'pending',
                'payment_method'   => 'cash_on_delivery', // or stripe later
                'total'            => $totalPrice,
                'shipping_name'    => $request->shipping_name,
                'shipping_phone'   => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'notes'            => $request->notes,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity'],
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // Clear cart after successful order
            session()->forget('cart');

            return redirect()->route('shop.orders.show', $order)
                ->with('success', 'Your order has been placed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Order create error', ['error' => $e->getMessage()]);
            return back()->withErrors('Something went wrong while creating your order.');
        }
    }
}
