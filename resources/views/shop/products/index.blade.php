@extends('layouts.store')

@section('title', 'Products')

@section('content')
<div class="bg-gradient-to-br from-slate-50 via-white to-slate-50 min-h-screen">
    {{-- HERO --}}
    <div class="relative bg-gradient-to-br from-green-600 via-emerald-600 to-teal-700 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 py-20 md:py-28 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full mb-6 border border-white/30">
                        <span class="text-sm font-semibold">Premium Tech Collection</span>
                    </div>

                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                        Discover Amazing
                        <span class="block bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            Mobile Deals
                        </span>
                    </h1>

                    <p class="text-xl md:text-2xl text-green-50 mb-8 leading-relaxed max-w-xl">
                        Explore our curated selection of premium smartphones and accessories at unbeatable prices.
                    </p>

                    <div class="flex gap-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center border border-white/30">
                                📱
                            </div>
                            <div>
                                <div class="text-3xl font-bold">{{ $products->count() }}+</div>
                                <div class="text-green-100 text-sm">Products</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center border border-white/30">
                                ✅
                            </div>
                            <div>
                                <div class="text-3xl font-bold">100%</div>
                                <div class="text-green-100 text-sm">Authentic</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cover image --}}
                <div class="relative hidden lg:block">
                    <div class="relative z-10">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/20 to-orange-400/20 rounded-3xl blur-3xl"></div>
                        <img
                            src="/cover3.jpg"
                            alt="Premium Smartphones"
                            class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover border-4 border-white/20"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- SIDEBAR FILTERS --}}
            <div class="lg:w-80">
                <form
                    method="GET"
                    action="{{ route('products.index') }}"
                    class="bg-white rounded-3xl shadow-xl p-6 sticky top-24 border border-gray-100 space-y-8"
                >
                    {{-- Categories --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full"></span>
                            Categories
                        </h3>
                        <div class="space-y-2">
                            <button
                                type="submit"
                                name="category"
                                value=""
                                class="w-full text-left px-5 py-3.5 rounded-2xl transition-all font-semibold text-sm
                                    {{ empty($categorySlug)
                                        ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30 scale-105'
                                        : 'bg-gray-50 text-gray-700 hover:bg-gray-100'
                                    }}"
                            >
                                All Products
                            </button>

                            @foreach($categories as $cat)
                                <button
                                    type="submit"
                                    name="category"
                                    value="{{ $cat->slug }}"
                                    class="w-full text-left px-5 py-3.5 rounded-2xl transition-all font-semibold text-sm
                                        {{ $categorySlug === $cat->slug
                                            ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30 scale-105'
                                            : 'bg-gray-50 text-gray-700 hover:bg-gray-100'
                                        }}"
                                >
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price range --}}
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
                                    class="w-1/2 px-3 py-2 border rounded-lg text-sm"
                                    value="{{ $minPrice ?? '' }}"
                                    placeholder="Min"
                                />
                                <input
                                    type="number"
                                    name="max_price"
                                    class="w-1/2 px-3 py-2 border rounded-lg text-sm"
                                    value="{{ $maxPrice ?? '' }}"
                                    placeholder="Max"
                                />
                            </div>
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
                            class="w-full px-5 py-3.5 border-2 border-gray-200 rounded-2xl bg-gray-50 font-semibold text-sm text-gray-700"
                        >
                            <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>✨ Newest First</option>
                            <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>💰 Price: Low to High</option>
                            <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>💎 Price: High to Low</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3 rounded-2xl hover:shadow-lg transition-all"
                    >
                        Apply Filters
                    </button>
                </form>
            </div>

            {{-- PRODUCT GRID --}}
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-3 rounded-2xl border-2 border-green-100">
                        <p class="text-gray-700 font-semibold text-sm">
                            <span class="text-green-600 font-bold text-lg">{{ $filteredProducts->count() }}</span>
                            <span class="text-gray-500 mx-2">of</span>
                            <span class="text-gray-800 font-bold text-lg">{{ $products->count() }}</span>
                            <span class="text-gray-500 ml-2">products</span>
                        </p>
                    </div>
                </div>

                @if($filteredProducts->isEmpty())
                    <div class="bg-white rounded-3xl shadow-xl p-16 text-center border-2 border-gray-100">
                        <div class="w-32 h-32 bg-gradient-to-br from-green-50 to-emerald-50 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-green-100">
                            🛒
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">No products found</h3>
                        <p class="text-lg text-gray-600 mb-10 max-w-md mx-auto">
                            Try adjusting your filters or search term.
                        </p>
                        <a
                            href="{{ route('products.index') }}"
                            class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 text-white px-10 py-4 rounded-full font-bold hover:shadow-lg transition-all"
                        >
                            Reset Filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                        @foreach($filteredProducts as $product)
                            @php
                                $stockQty = (int)($product->quantity ?? 0);
                                $rating = (int)($product->average_rating ?? 0);
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

                                    <div class="absolute top-4 left-4">
                                        <span class="px-4 py-2 rounded-full text-xs font-bold
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
                                            @else ✗ Out of Stock @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="mb-3">
                                        <span class="text-xs text-green-600 font-bold uppercase tracking-wider bg-green-50 px-3 py-1 rounded-full">
                                            {{ $product->categoryRelation->name ?? 'Uncategorized' }}
                                        </span>
                                    </div>

                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <h3 class="text-lg font-bold text-gray-800 mb-3 hover:text-green-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                                            {{ $product->name }}
                                        </h3>
                                    </a>

                                    <div class="flex items-center gap-1 mb-5">
                                        @for($i=0; $i<5; $i++)
                                            <span class="text-sm {{ $i < $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                        @endfor
                                        <span class="text-sm text-gray-500 ml-2 font-semibold">({{ $product->average_rating ?? 0 }})</span>
                                    </div>

                                    <div class="flex items-center justify-between pt-5 border-t-2 border-gray-100">
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1 font-semibold">Price</p>
                                            <p class="text-2xl font-black text-gray-800">
                                                ${{ number_format($product->price, 2) }}
                                            </p>
                                        </div>

                                        <div class="flex gap-2">
                                            {{-- Add to Cart --}}
                                            <form method="POST" action="{{ route('cart.add', $product) }}">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button
                                                    type="submit"
                                                    @if($stockQty === 0) disabled @endif
                                                    class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-3.5 rounded-2xl hover:shadow-lg transition-all
                                                        disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed"
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
                                                    @if($isWishlisted)
                                                        @method('DELETE')
                                                    @endif
                                                    <button type="submit"
                                                        class="p-3.5 rounded-2xl transition-all wishlist-btn
                                                        {{ $isWishlisted ? 'bg-red-500 text-white' : 'bg-pink-100 text-pink-500 hover:bg-pink-200' }}"
                                                        title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                                        {{ $isWishlisted ? '❤️' : '🤍' }}
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}"
                                                   class="p-3.5 rounded-2xl transition-all bg-pink-100 text-pink-500 hover:bg-pink-200"
                                                   title="Login to add to wishlist">
                                                    🤍
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $filteredProducts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div id="flash-success" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
        <span>✓</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="flash-error" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
        <span>✗</span>
        {{ session('error') }}
    </div>
@endif

<script>
// Auto-hide flash messages
document.addEventListener('DOMContentLoaded', function() {
    const flashSuccess = document.getElementById('flash-success');
    const flashError = document.getElementById('flash-error');
    
    [flashSuccess, flashError].forEach(flash => {
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity 0.3s';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 300);
            }, 3000);
        }
    });
});

{{-- Wishlist AJAX --}}
@auth
document.addEventListener('DOMContentLoaded', function() {
    const wishlistForms = document.querySelectorAll('.wishlist-form');

    wishlistForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = this.querySelector('button');
            const productId = this.dataset.productId;
            const url = this.action;
            const methodInput = this.querySelector('input[name="_method"]');
            const method = methodInput ? 'DELETE' : 'POST';
            const token = this.querySelector('input[name="_token"]').value;

            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = '⏳';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    _method: method
                })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if(data.success) {
                    // Toggle between filled and empty heart
                    if(method === 'POST') {
                        // Added to wishlist
                        btn.textContent = '❤️';
                        btn.classList.remove('bg-pink-100', 'text-pink-500', 'hover:bg-pink-200');
                        btn.classList.add('bg-red-500', 'text-white');
                        btn.title = 'Remove from Wishlist';
                        
                        // Add DELETE method
                        if(!methodInput) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = '_method';
                            input.value = 'DELETE';
                            this.appendChild(input);
                        }
                        this.action = `/wishlist/${productId}`;
                    } else {
                        // Removed from wishlist
                        btn.textContent = '🤍';
                        btn.classList.remove('bg-red-500', 'text-white');
                        btn.classList.add('bg-pink-100', 'text-pink-500', 'hover:bg-pink-200');
                        btn.title = 'Add to Wishlist';
                        
                        // Remove DELETE method
                        if(methodInput) {
                            methodInput.remove();
                        }
                        this.action = `/wishlist/${productId}`;
                    }
                    
                    // Show toast notification (optional)
                    showToast(data.message || 'Wishlist updated!');
                } else {
                    btn.textContent = originalText;
                    alert(data.message || 'Something went wrong!');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                btn.textContent = originalText;
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });
    
    // Optional: Toast notification function
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
@endauth
</script>
@endsection