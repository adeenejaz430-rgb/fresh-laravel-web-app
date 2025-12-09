@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">Customers</h1>

        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <button
                type="button"
                onclick="alert('Export customers CSV here')"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
                ⭳
                <span class="ml-2">Export</span>
            </button>

            {{-- Optional: Add customer page if you want --}}
            {{-- <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                + <span class="ml-1">Add Customer</span>
            </a> --}}
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.customers.index') }}">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            🔍
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Search customers..."
                        />
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700">Status:</span>
                        <select
                            name="status"
                            class="block pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                            onchange="this.form.submit()"
                        >
                            <option value="">All</option>
                            <option value="active"   {{ $status === 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Orders
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Spent
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Joined
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        @php
                            $statusVal = $customer->status ?? 'active';
                            $statusClass = $statusVal === 'active'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-gray-100 text-gray-800';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                                        👤
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $customer->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $customer->orders_count }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{-- You can add last order date here if you want --}}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($customer->total_spent ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($statusVal) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $customer->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a
                                        href="mailto:{{ $customer->email }}"
                                        class="text-gray-400 hover:text-gray-500"
                                        title="Email customer"
                                    >
                                        ✉
                                    </a>
                                    {{-- If you create admin.customers.show, link it here --}}
                                    {{-- <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">View</a> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
