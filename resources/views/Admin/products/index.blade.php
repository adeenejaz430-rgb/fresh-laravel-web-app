@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">Products Management</h1>

        <a
            href="{{ route('admin.products.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
        >
            <span class="mr-2">＋</span>
            Add New Product
        </a>
    </div>

    {{-- Filters and Search --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}">
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
                        placeholder="Search products..."
                    />
                </div>

                <div class="flex items-center">
                    <span class="mr-2 text-gray-500 text-sm">Category:</span>
                    <select
                        name="category"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                        onchange="this.form.submit()"
                    >
                        <option value="">All</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string)$cat->id === (string)$category ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- Products table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Featured
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                       @php
    $images = $product->images ?? [];  // ✅ CORRECT - already an array
    $firstImage = $images[0] ?? $product->image ?? null;
@endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        @if($firstImage)
                                            <img
                                                src="{{ $firstImage }}"
                                                alt="{{ $product->name }}"
                                                class="h-10 w-10 rounded-md object-cover"
                                                onerror="this.onerror=null;this.src='/images/placeholder.jpg';"
                                            >
                                        @else
                                            <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                                No Img
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">#{{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
    {{ $product->categoryRelation->name ?? 'Uncategorized' }}
</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $product->quantity < 10 ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                    {{ $product->quantity }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->featured ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->featured ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this product?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
