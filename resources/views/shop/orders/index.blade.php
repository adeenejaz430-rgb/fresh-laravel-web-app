@extends('layouts.store')

@section('title', 'Your Orders')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">Your Orders</h1>

    {{-- if not logged in, middleware will already redirect; this is just a safety message --}}
    @guest
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <h2 class="text-xl font-medium text-gray-900 mb-2">
                Please sign in to view your orders
            </h2>
            <p class="text-gray-500 mb-6">
                Orders are linked to your account. Sign in and try again.
            </p>
            <a
                href="{{ route('login') }}"
                class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
            >
                Go to Login
            </a>
        </div>
    @else
        @if($orders->isEmpty())
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <h2 class="text-xl font-medium text-gray-900 mb-2">
                    No orders found
                </h2>
                <p class="text-gray-500 mb-6">
                    You haven't placed any orders yet.
                </p>
                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                >
                    Start Shopping
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    @php
                        $status = $order->status;
                        $map = [
                            'pending'    => ['Pending', 'bg-yellow-100 text-yellow-800'],
                            'paid'       => ['Paid', 'bg-yellow-100 text-yellow-800'],
                            'processing' => ['Processing', 'bg-yellow-100 text-yellow-800'],
                            'shipped'    => ['Shipped', 'bg-blue-100 text-blue-800'],
                            'delivered'  => ['Delivered', 'bg-green-100 text-green-800'],
                            'cancelled'  => ['Cancelled', 'bg-red-100 text-red-800'],
                            'failed'     => ['Payment Failed', 'bg-red-100 text-red-800'],
                            'refunded'   => ['Refunded', 'bg-red-100 text-red-800'],
                        ];
                        [$statusText, $statusClass] = $map[$status] ?? ['Unknown', 'bg-gray-100 text-gray-800'];
                    @endphp
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Order #{{ $order->orderNumber ?? $order->id }}
                                </h2>
                                <p class="text-sm text-gray-500">
                                    Placed on {{ optional($order->created_at)->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">
                                Items
                            </h3>
                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $item->name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Qty: {{ $item->quantity }}
                                            </p>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            ${{ number_format($item->price ?? 0, 2) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                            <p class="text-sm font-medium text-gray-700">Total</p>
                            <p class="text-lg font-bold text-gray-900">
                                ${{ number_format($order->total_price ?? 0, 2) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endguest
</div>
@endsection
