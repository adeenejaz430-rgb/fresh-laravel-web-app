@extends('layouts.store')

@section('title', 'Search Results')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <a href="{{ route('shop.home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            ← Back to Home
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-4">Search Results</h1>
    <p class="text-gray-600 mb-8">
        @if($query)
            Showing results for "{{ $query }}"
        @else
            Enter a search term to find products
        @endif
    </p>

    @if(!$query)
        <p class="text-gray-500">Use the search bar to look for products.</p>
    @elseif($results->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <div class="w-16 h-16 mx-auto bg-gray-200 rounded-full flex items-center justify-center mb-4">
                🔍
            </div>
            <h2 class="text-xl font-medium mb-2">No results found</h2>
            <p class="text-gray-500 mb-6">
                Try adjusting your search to find what you're looking for.
            </p>
            <a href="{{ route('products.index') }}">
                <button class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Browse All Products
                </button>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($results as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <div class="bg-gray-200 aspect-w-3 aspect-h-2">
                            <img
                                src="{{ $product->image ?? $product->images[0] ?? '/images/placeholder.jpg' }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </a>
                    <div class="p-4">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="text-lg font-medium mb-2 hover:text-blue-600">{{ $product->name }}</h3>
                        </a>
                        <p class="text-blue-600 font-bold mb-4">${{ number_format($product->price, 2) }}</p>
                        <form method="POST" action="{{ route('cart.add', $product->id) }}" class="flex space-x-2">
                            @csrf
                            <button
                                type="submit"
                                class="flex-1 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 flex items-center justify-center"
                            >
                                🛒 Add to Cart
                            </button>
                            {{-- wishlist button can post to wishlist route --}}
                            <button
                                type="submit"
                                formaction="{{ route('wishlist.store', $product->id) }}"
                                class="p-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200"
                            >
                                ♥
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
