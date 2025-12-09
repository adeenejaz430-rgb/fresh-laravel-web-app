@extends('layouts.store')

@section('title', 'My Wishlist')

@section('content')
<div class="bg-gradient-to-br from-slate-50 via-white to-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-4xl md:text-5xl font-black text-gray-800 mb-3">
                My Wishlist ❤️
            </h1>
            <p class="text-gray-600 text-lg">
                Your favorite products saved for later
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-2 border-green-200 text-green-800 px-6 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if($items->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl shadow-xl p-16 text-center border-2 border-gray-100">
                <div class="w-32 h-32 bg-gradient-to-br from-pink-50 to-red-50 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-pink-100">
                    <span class="text-6xl">💔</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Your wishlist is empty</h3>
                <p class="text-lg text-gray-600 mb-10 max-w-md mx-auto">
                    Start adding products you love to your wishlist!
                </p>
                <a
                    href="{{ route('products.index') }}"
                    class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 text-white px-10 py-4 rounded-full font-bold hover:shadow-lg transition-all"
                >
                    Browse Products
                </a>
            </div>
        @else
            {{-- Wishlist Count --}}
            <div class="mb-8">
                <div class="inline-block bg-gradient-to-r from-pink-50 to-red-50 px-6 py-3 rounded-2xl border-2 border-pink-100">
                    <p class="text-gray-700 font-semibold">
                        <span class="text-red-600 font-bold text-lg">{{ $items->count() }}</span>
                        <span class="text-gray-500 ml-2">{{ Str::plural('item', $items->count()) }} in your wishlist</span>
                    </p>
                </div>
            </div>

            {{-- Wishlist Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($items as $item)
                    @php
                        // Check if product exists
                        if (!$item->product) {
                            continue; // Skip this item if product is deleted
                        }
                        
                        $product = $item->product;
                        $stockQty = (int)($product->quantity ?? 0);
                        $rating = (int)($product->average_rating ?? 0);
                        
                        // Handle images properly
                        if (is_string($product->images)) {
                            $images = json_decode($product->images, true) ?: [];
                        } else {
                            $images = is_array($product->images) ? $product->images : [];
                        }
                        
                        $firstImage = !empty($images) ? $images[0] : '/images/placeholder.jpg';
                    @endphp

                    <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-gray-100 hover:border-pink-300 group">
                        {{-- Product Image --}}
                        <div class="relative h-64 overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img
                                    src="{{ $firstImage }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    onerror="this.onerror=null;this.src='/images/placeholder.jpg';"
                                />
                            </a>

                            {{-- Stock Badge --}}
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold
                                    @if($stockQty > 5)
                                        bg-green-500/90 text-white
                                    @elseif($stockQty > 0)
                                        bg-yellow-400/90 text-gray-800
                                    @else
                                        bg-red-500/90 text-white
                                    @endif
                                ">
                                    @if($stockQty > 5) ✓ In Stock
                                    @elseif($stockQty > 0) ⚠ Low Stock
                                    @else ✗ Out of Stock
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Product Info --}}
                        <div class="p-5">
                            {{-- Category --}}
                            <div class="mb-3">
                                <span class="text-xs text-green-600 font-bold uppercase tracking-wider bg-green-50 px-3 py-1 rounded-full">
                                    {{ $product->categoryRelation->name ?? 'Uncategorized' }}
                                </span>
                            </div>

                            {{-- Product Name --}}
                            <a href="{{ route('products.show', $product->slug) }}">
                                <h3 class="text-lg font-bold text-gray-800 mb-3 hover:text-green-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            {{-- Rating --}}
                            <div class="flex items-center gap-1 mb-4">
                                @for($i = 0; $i < 5; $i++)
                                    <span class="text-sm {{ $i < $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                                <span class="text-sm text-gray-500 ml-2 font-semibold">({{ $product->average_rating ?? 0 }})</span>
                            </div>

                            {{-- Price --}}
                            <div class="mb-4 pt-4 border-t-2 border-gray-100">
                                <p class="text-xs text-gray-500 mb-1 font-semibold">Price</p>
                                <p class="text-2xl font-black text-gray-800">
                                    ${{ number_format($product->price, 2) }}
                                </p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-2">
                                {{-- Add to Cart --}}
                                <form method="POST" action="{{ route('cart.add', $product->id) }}" class="flex-1">
                                    @csrf
                                    <button
                                        type="submit"
                                        @if($stockQty === 0) disabled @endif
                                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 rounded-2xl font-semibold hover:shadow-lg transition-all
                                            disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed"
                                    >
                                        🛒 Add to Cart
                                    </button>
                                </form>

                                {{-- Remove from Wishlist --}}
                                <form method="POST" action="{{ route('wishlist.remove', $product->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-3 bg-red-100 text-red-600 rounded-2xl hover:bg-red-200 transition-all"
                                        title="Remove from wishlist"
                                    >
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Clear All Button --}}
            @if($items->count() > 1)
                <div class="mt-10 text-center">
                    <form method="POST" action="{{ route('wishlist.clear') }}" onsubmit="return confirm('Are you sure you want to clear your entire wishlist?')">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="inline-block bg-red-500 text-white px-8 py-3 rounded-full font-bold hover:bg-red-600 transition-all"
                        >
                            Clear Entire Wishlist
                        </button>
                    </form>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection