
@extends('layouts.store')

@section('title', $product->name)

@section('content')
@php
    // ✅ Fixed: Handle both null and empty array cases
    $images = (!empty($product->images) && is_array($product->images)) 
        ? $product->images 
        : ['/flower.png'];
    
    $stock = (int)($product->stock ?? $product->quantity ?? 0);
    $rating = (int)($product->rating ?? $product->average_rating ?? 0);
@endphp

<div class="bg-gray-50 min-h-screen">
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
                ← Back to Products
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Images --}}
            <div class="space-y-4">
                <div class="relative bg-white rounded-3xl overflow-hidden shadow-lg border-2 border-gray-100 aspect-square">
                    <img
                        src="{{ $images[0] ?? '/flower.png' }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute top-6 left-6">
                        <span class="px-4 py-2 rounded-full text-sm font-bold shadow-lg
                            @if($stock > 5) bg-green-500 text-white
                            @elseif($stock > 0) bg-yellow-400 text-gray-800
                            @else bg-red-500 text-white @endif
                        ">
                            @if($stock > 5) In Stock
                            @elseif($stock > 0) Only {{ $stock }} Left
                            @else Out of Stock @endif
                        </span>
                    </div>
                </div>

                @if(count($images) > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($images as $img)
                            <div class="relative aspect-square rounded-xl overflow-hidden border-2 border-gray-200 hover:border-green-400 transition-colors cursor-pointer">
                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="space-y-6">
                <div>
                    <span class="inline-block bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-sm font-semibold">
                        {{ $product->categoryRelation->name ?? $product->category }}
                    </span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 leading-tight">
                    {{ $product->name }}
                </h1>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        @for($i=0; $i<5; $i++)
                            <span class="text-lg {{ $i < $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                    </div>
                    <span class="text-gray-600 font-medium">({{ $rating }} rating)</span>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-yellow-50 rounded-2xl p-6 border-2 border-green-200">
                    <p class="text-4xl font-bold text-gray-800">${{ number_format($product->price, 2) }}</p>
                    <p class="text-gray-600 mt-2">Free shipping on orders over $50</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Description</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $product->description ?? 'This premium quality product is carefully crafted to bring elegance and style to your space.' }}
                    </p>
                </div>

                {{-- Quantity + Add to cart --}}
                <form
                    method="POST"
                   action="{{ route('cart.add', $product) }}"
                    class="space-y-6"
                >
                    @csrf
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Quantity</h3>
                        <div class="flex items-center gap-3">
                            <input
                                type="number"
                                name="quantity"
                                min="1"
                                max="{{ $stock }}"
                                value="1"
                                class="w-20 h-12 border-2 border-gray-300 rounded-xl text-center font-bold text-lg focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                @if($stock === 0) disabled @endif
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button
                            type="submit"
                            @if($stock === 0) disabled @endif
                            class="py-4 px-6 rounded-xl flex items-center justify-center gap-2 font-bold text-lg transition-all shadow-md
                                @if($stock > 0)
                                    bg-green-500 hover:bg-green-600 text-white hover:scale-105
                                @else
                                    bg-gray-300 text-gray-500 cursor-not-allowed
                                @endif
                            ">
                            <span class="material-symbols-outlined">shopping_cart</span>
                            {{ $stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                        </button>

                        {{-- Wishlist button --}}
                        <button
                            type="button"
                            class="py-4 px-6 rounded-xl flex items-center justify-center gap-2 font-bold text-lg border-2 bg-white text-gray-700 border-gray-300 hover:border-green-400 hover:bg-green-50 transition-all hover:scale-105"
                        >
                            <span class="material-symbols-outlined">favorite</span>
                            Add to Wishlist
                        </button>
                    </div>
                </form>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-gray-100">
                        <span class="material-symbols-outlined text-3xl text-green-600">local_shipping</span>
                        <p class="text-sm font-semibold text-gray-800 mt-2">Free Shipping</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-gray-100">
                        <span class="material-symbols-outlined text-3xl text-green-600">lock</span>
                        <p class="text-sm font-semibold text-gray-800 mt-2">Secure Payment</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-gray-100">
                        <span class="material-symbols-outlined text-3xl text-green-600">package</span>
                        <p class="text-sm font-semibold text-gray-800 mt-2">Easy Returns</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related products --}}
        @if($relatedProducts->isNotEmpty())
            <div class="mt-20">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <a
                            href="{{ route('products.show', $related->slug) }}"
                            class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border-2 border-gray-100 hover:border-green-400 group"
                        >
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                @php
                                    $relatedImages = (!empty($related->images) && is_array($related->images)) 
                                        ? $related->images 
                                        : ['/flower.png'];
                                @endphp
                                <img
                                    src="{{ $relatedImages[0] ?? '/flower.png' }}"
                                    alt="{{ $related->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                />
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 mb-2 line-clamp-1">{{ $related->name }}</h3>
                                <p class="text-2xl font-bold text-gray-800">${{ number_format($related->price, 2) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Display flash messages --}}
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
@endif
@endsection