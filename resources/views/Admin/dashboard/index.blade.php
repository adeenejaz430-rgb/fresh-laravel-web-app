@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-4">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="rounded-full bg-blue-100 p-3 mr-4">
                💵
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Sales</p>
                <p class="text-2xl font-bold text-gray-900">
                    ${{ number_format($totalSales, 2) }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="rounded-full bg-green-100 p-3 mr-4">
                🛒
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="rounded-full bg-purple-100 p-3 mr-4">
                👥
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Customers</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="rounded-full bg-orange-100 p-3 mr-4">
                📦
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
            </div>
        </div>
    </div>

    {{-- Sales overview (simple bar style) --}}
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">Sales Overview</h2>
            <span class="text-sm font-medium text-green-500">
                +{{ $salesGrowthPercent }}% from last period
            </span>
        </div>

        @if($salesData->count())
            @php
                $max = max($salesData->pluck('sales')->all());
            @endphp
            <div class="w-full flex h-48 items-end space-x-2">
                @foreach($salesData as $row)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-blue-500 rounded-t"
                             style="height: {{ $max > 0 ? ($row['sales'] / $max) * 100 : 0 }}%">
                        </div>
                        <div class="text-xs mt-2 text-gray-600">{{ $row['month'] }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No sales data yet.</p>
        @endif
    </div>

    {{-- Recent orders --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-medium text-gray-900">Recent Orders</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                {{ $order['_id'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order['customerName'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($order['createdAt'])->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($order['total'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status = strtolower($order['status']);
                                    $classes = match($status) {
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'shipped' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $classes }}">
                                    {{ $order['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No recent orders yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
