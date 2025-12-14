{{-- resources/views/shop/categories/show.blade.php --}}

@extends('layouts.store')

@section('title', $category->name . ' - Products')

@section('content')
<div class="bg-gradient-to-br from-slate-50 via-white to-slate-50 min-h-screen">
    {{-- Hero Section with Category Info --}}
    <div class="relative bg-gradient-to-br from-green-600 via-emerald-600 to-teal-700 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 py-16 md:py-24 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-white/80 mb-8">
               
                <span>/</span>
                <a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Products</a>
                <span>/</span>
                <span class="text-white font-semibold">{{ $category->name }}</span>
            </nav>

            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full mb-6 border border-white/30">
                        <span class="text-sm font-semibold">{{ $category->name }} Collection</span>
                    </div>

                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                        Explore Our
                        <span class="block bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            {{ $category->name }}
                        </span>
                    </h1>

                    @if($category->description)
                        <p class="text-xl md:text-2xl text-green-50 mb-8 leading-relaxed max-w-xl">
                            {{ $category->description }}
                        </p>
                    @else
                        <p class="text-xl md:text-2xl text-green-50 mb-8 leading-relaxed max-w-xl">
                            Discover our premium collection of {{ strtolower($category->name) }} with exceptional quality and style.
                        </p>
                    @endif

                    <div class="flex gap-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center border border-white/30">
                                📦
                            </div>
                            <div>
                                <div class="text-3xl font-bold">{{ $products->total() }}+</div>
                                <div class="text-green-100 text-sm">Products</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center border border-white/30">
                                ⭐
                            </div>
                            <div>
                                <div class="text-3xl font-bold">100%</div>
                                <div class="text-green-100 text-sm">Authentic</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Category Image --}}
                @if($category->image)
                    <div class="relative hidden lg:block">
                        <div class="relative z-10">
                            <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/20 to-orange-400/20 rounded-3xl blur-3xl"></div>
                            <img
                                src="{{ $category->image }}"
                                alt="{{ $category->name }}"
                                class="relative rounded-3xl shadow-2xl w-full h-[400px] object-cover border-4 border-white/20"
                            />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Filters --}}
            <div class="lg:w-80">
                <form
                    method="GET"
                    action="{{ route('categories.show', $category->slug) }}"
                    class="bg-white rounded-3xl shadow-xl p-6 sticky top-24 border border-gray-100 space-y-8"
                    id="filterForm"
                >
                    {{-- Price Range --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full"></span>
                            Price Range
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-3 rounded-xl">
                                <span class="text-sm font-bold text-gray-700">${{ $minPrice ?? 0 }}</span>
                                <div class="w-px h-6 bg-green-300"></div>
                                <span class="text-sm font-bold text-gray-700">${{ $maxPrice ?? 1000 }}</span>
                            </div>
                            <div class="flex gap-2">
                                <input
                                    type="number"
                                    name="min_price"
                                    class="w-1/2 px-3 py-2 border-2 border-gray-200 rounded-xl text-sm focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                    value="{{ request('min_price') }}"
                                    placeholder="Min"
                                />
                                <input
                                    type="number"
                                    name="max_price"
                                    class="w-1/2 px-3 py-2 border-2 border-gray-200 rounded-xl text-sm focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                    value="{{ request('max_price') }}"
                                    placeholder="Max"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Availability --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full"></span>
                            Availability
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="in_stock"
                                    value="1"
                                    {{ request('in_stock') ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-2 border-gray-300 text-green-600 focus:ring-2 focus:ring-green-200"
                                />
                                <span class="text-gray-700 font-medium group-hover:text-green-600 transition-colors">In Stock Only</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="featured"
                                    value="1"
                                    {{ request('featured') ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-2 border-gray-300 text-green-600 focus:ring-2 focus:ring-green-200"
                                />
                                <span class="text-gray-700 font-medium group-hover:text-green-600 transition-colors">Featured Only</span>
                            </label>
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full"></span>
                            Sort By
                        </h3>
                        <select
                            name="sort"
                            class="w-full px-5 py-3.5 border-2 border-gray-200 rounded-2xl bg-gray-50 font-semibold text-sm text-gray-700 focus:border-green-500 focus:ring-2 focus:ring-green-200"
                            onchange="document.getElementById('filterForm').submit()"
                        >
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>✨ Newest First</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>💰 Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>💎 Price: High to Low</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>🔤 Name: A-Z</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3.5 rounded-2xl hover:shadow-lg transition-all transform hover:scale-105"
                        >
                            Apply Filters
                        </button>
                        
                        @if(request()->hasAny(['min_price', 'max_price', 'in_stock', 'featured', 'sort']))
                            <a
                                href="{{ route('categories.show', $category->slug) }}"
                                class="block w-full text-center border-2 border-gray-300 text-gray-700 font-semibold py-3 rounded-2xl hover:bg-gray-50 transition-all"
                            >
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Products Grid --}}
            <div class="flex-1">
                {{-- Results Header --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-3 rounded-2xl border-2 border-green-100">
                        <p class="text-gray-700 font-semibold text-sm">
                            Showing
                            <span class="text-green-600 font-bold text-lg mx-1">{{ $products->count() }}</span>
                            of
                            <span class="text-gray-800 font-bold text-lg mx-1">{{ $products->total() }}</span>
                            products
                        </p>
                    </div>
                </div>

                @if($products->isEmpty())
                    {{-- Empty State --}}
                    <div class="bg-white rounded-3xl shadow-xl p-16 text-center border-2 border-gray-100">
                        <div class="w-32 h-32 bg-gradient-to-br from-green-50 to-emerald-50 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-green-100">
                            <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">No products found</h3>
                        <p class="text-lg text-gray-600 mb-10 max-w-md mx-auto">
                            No products match your current filters. Try adjusting your search criteria.
                        </p>
                        <a
                            href="{{ route('categories.show', $category->slug) }}"
                            class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 text-white px-10 py-4 rounded-full font-bold hover:shadow-lg transition-all"
                        >
                            Clear All Filters
                        </a>
                    </div>
                @else
                    {{-- Products Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                        @foreach($products as $product)
                            @php
                                $stockQty = (int)($product->quantity ?? 0);
                                $images = is_array($product->images) ? $product->images : [];
                                $firstImage = $images[0] ?? '/images/placeholder.jpg';
                                
                                $isWishlisted = false;
                                if(auth()->check()) {
                                    $isWishlisted = auth()->user()->wishlistProducts->contains($product->id);
                                }
                            @endphp

                            <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-gray-100 hover:border-green-400 group">
                                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img
                                            src="{{ $firstImage }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                            onerror="this.onerror=null;this.src='/images/placeholder.jpg';"
                                        />
                                    </a>

                                    {{-- Badges --}}
                                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                                        @if($product->featured)
                                            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-yellow-400 to-orange-400 text-white shadow-lg flex items-center gap-1 w-fit">
                                                <span>⭐</span>
                                                <span>Featured</span>
                                            </span>
                                        @endif
                                        
                                        <span class="px-4 py-2 rounded-full text-xs font-bold
                                            @if($stockQty > 5) bg-green-500/90 text-white
                                            @elseif($stockQty > 0) bg-yellow-400/90 text-gray-800
                                            @else bg-red-500/90 text-white
                                            @endif
                                        ">
                                            @if($stockQty > 5) ✓ In Stock
                                            @elseif($stockQty > 0) ⚠ Only {{ $stockQty }} left
                                            @else ✗ Out of Stock
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <h3 class="text-lg font-bold text-gray-800 mb-3 hover:text-green-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                                            {{ $product->name }}
                                        </h3>
                                    </a>

                                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                                        {{ $product->description ?? 'Premium quality product.' }}
                                    </p>

                                    <div class="flex items-center justify-between pt-5 border-t-2 border-gray-100">
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1 font-semibold">Price</p>
                                            <p class="text-2xl font-black text-gray-800">
                                                ${{ number_format($product->price, 2) }}
                                            </p>
                                        </div>

                                        <div class="flex gap-2">
                                            {{-- Add to Cart --}}
                                            <form class="add-to-cart-form" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button
                                                    type="submit"
                                                    @if($stockQty === 0) disabled @endif
                                                    class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-3.5 rounded-2xl hover:shadow-lg transition-all disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed transform hover:scale-105"
                                                    title="{{ $stockQty > 0 ? 'Add to Cart' : 'Out of Stock' }}"
                                                >
                                                    🛒
                                                </button>
                                            </form>

                                            {{-- Wishlist --}}
                                            @auth
                                                <form method="POST"
                                                      action="{{ $isWishlisted ? route('wishlist.remove', $product->id) : route('wishlist.add', $product->id) }}"
                                                      class="wishlist-form"
                                                      data-product-id="{{ $product->id }}">
                                                    @csrf
                                                    @if($isWishlisted) @method('DELETE') @endif
                                                    <button type="submit"
                                                        class="p-3.5 rounded-2xl transition-all
                                                        {{ $isWishlisted ? 'bg-red-500 text-white' : 'bg-pink-100 text-pink-500 hover:bg-pink-200' }}">
                                                        {{ $isWishlisted ? '❤️' : '🤍' }}
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}"
                                                   class="p-3.5 rounded-2xl transition-all bg-pink-100 text-pink-500 hover:bg-pink-200">
                                                    🤍
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('shop.partials.category-scripts')
@endsection