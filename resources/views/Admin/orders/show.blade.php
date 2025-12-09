@extends('admin.layouts.app')

@section('title', 'Order Detail')

@section('content')
@php
    $paymentStatus = $order->is_paid ? 'Paid' : 'Pending';
    $shipping = [
        'name'    => $order->shipping_name ?? $order->shippingAddress['name'] ?? null,
        'address' => $order->shipping_address ?? $order->shippingAddress['address'] ?? $order->shippingAddress['street'] ?? null,
        'city'    => $order->shipping_city ?? $order->shippingAddress['city'] ?? null,
        'state'   => $order->shipping_state ?? $order->shippingAddress['state'] ?? null,
        'zip'     => $order->shipping_postal_code ?? $order->shippingAddress['postalCode'] ?? $order->shippingAddress['zip'] ?? null,
        'country' => $order->shipping_country ?? $order->shippingAddress['country'] ?? 'Unknown',
    ];

    $statusClass = match(strtolower($order->status)) {
        'delivered'  => 'bg-green-100 text-green-800',
        'processing','paid' => 'bg-blue-100 text-blue-800',
        'shipped'    => 'bg-yellow-100 text-yellow-800',
        'cancelled','failed','refunded' => 'bg-red-100 text-red-800',
        default      => 'bg-gray-100 text-gray-800',
    };

    $payClass = $order->is_paid
        ? 'bg-green-100 text-green-800'
        : 'bg-yellow-100 text-yellow-800';
@endphp

<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('admin.orders.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                ←
            </a>
            <h1 class="text-2xl font-bold text-gray-900">
                Order {{ $order->order_number ?? $order->id }}
            </h1>
        </div>

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
            🖨
            <span class="ml-2">Print Order</span>
        </button>
    </div>

    @if(session('error'))
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-100 px-4 py-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order summary --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
            </div>

            <div class="p-6 space-y-6">
                <div class="flex flex-wrap justify-between">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Order Date</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $order->created_at?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Order Status</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Payment Status</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payClass }}">
                            {{ $paymentStatus }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $order->payment_method ?? 'Card' }}
                        </p>
                    </div>
                </div>

                {{-- Items --}}
                <div class="border-t pt-6">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Items</h3>

                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center">
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                    <div class="h-full w-full flex items-center justify-center bg-gray-200 text-gray-500 text-xs">
                                        Img
                                    </div>
                                </div>

                                <div class="ml-4 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $item->name ?? optional($item->product)->name ?? 'Product' }}
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Qty: {{ $item->quantity }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        ${{ number_format($item->price, 2) }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Totals --}}
                <div class="border-t pt-6 space-y-2">
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-500">Subtotal</p>
                        <p class="text-gray-900 font-medium">
                            ${{ number_format($order->items_price ?? $order->total_price ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-500">Shipping</p>
                        <p class="text-gray-900 font-medium">
                            ${{ number_format($order->shipping_price ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-500">Tax</p>
                        <p class="text-gray-900 font-medium">
                            ${{ number_format($order->tax_price ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="flex justify-between mt-4 pt-4 border-t">
                        <p class="text-base font-medium text-gray-900">Total</p>
                        <p class="text-base font-medium text-gray-900">
                            ${{ number_format($order->total_price ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column: status + customer + shipping --}}
        <div class="space-y-6">
            {{-- Update status --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-medium text-gray-900">Update Status</h2>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Order Status
                            </label>
                            <select
                                name="status"
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                            >
                                @foreach(['processing', 'shipped', 'delivered', 'cancelled'] as $st)
                                    <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Customer info --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-medium text-gray-900">Customer Information</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-900">
                        {{ $shipping['name'] ?? optional($order->user)->name ?? 'Customer' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">{{ $order->email }}</p>
                </div>
            </div>

            {{-- Shipping address --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-medium text-gray-900">Shipping Address</h2>
                </div>
                <div class="p-6 text-sm text-gray-900 space-y-1">
                    <p>{{ $shipping['address'] }}</p>
                    <p>
                        {{ $shipping['city'] }}, {{ $shipping['state'] }} {{ $shipping['zip'] }}
                    </p>
                    <p>{{ $shipping['country'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
