@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
            Orders Management
        </h1>

        <button
            type="button"
            onclick="alert('Implement export here (CSV, etc.)')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
            ⭳
            <span class="ml-2">Export Orders</span>
        </button>
    </div>

    {{-- Filters + search --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                <div class="relative flex-1 mb-4 md:mb-0">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        🔍
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Search by ID, email..."
                    />
                </div>

                <div class="flex items-center">
                    <span class="mr-2 text-gray-500 text-sm">Status:</span>
                    <select
                        name="status"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                        onchange="this.form.submit()"
                    >
                        <option value="">All Statuses</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- Orders table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Payment
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        @php
                            $statusClass = match(strtolower($order->status)) {
                                'delivered'  => 'bg-green-100 text-green-800',
                                'processing','paid' => 'bg-blue-100 text-blue-800',
                                'shipped'    => 'bg-yellow-100 text-yellow-800',
                                'cancelled','failed','refunded' => 'bg-red-100 text-red-800',
                                default      => 'bg-gray-100 text-gray-800',
                            };

                            $payment = $order->is_paid ? 'Paid' : 'Pending';
                            $payClass = match(strtolower($payment)) {
                                'paid'      => 'bg-green-100 text-green-800',
                                'pending'   => 'bg-yellow-100 text-yellow-800',
                                'refunded'  => 'bg-red-100 text-red-800',
                                default     => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                {{ $order->order_number ?? $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ optional($order->user)->name ?? 'Guest' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $order->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order->created_at?->format('d M Y') ?? '-' }}
                                <div class="text-sm text-gray-500">
                                    {{ $order->items()->count() }} items
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${{ number_format($order->total_price ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($order->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payClass }}">
                                    {{ $payment }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a
                                    href="{{ route('admin.orders.show', $order) }}"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
