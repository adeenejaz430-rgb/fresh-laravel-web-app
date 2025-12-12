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
                        
                        // Use the accessor that properly converts storage path to URL
                        $firstImage = $product->main_image_url;
                    @endphp

                    <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-gray-100 hover:border-pink-300 group">
                        {{-- Product Image --}}
                        <div class="relative h-64 overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img
                                    src="{{ $firstImage }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    onerror="this.onerror=null;this.src='{{ asset('/flower.png') }}';"
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
                                <form class="add-to-cart-form flex-1" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
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

@push('scripts')
<script>
(function() {
    // Update cart count function
    function updateCartCount() {
        fetch('/cart/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            const cartBadges = document.querySelectorAll('.cart-count-badge, #cartItemCount');
            cartBadges.forEach(badge => {
                if (badge) {
                    badge.textContent = data.count || 0;
                    if (data.count > 0) {
                        badge.classList.remove('hidden');
                    }
                }
            });
        })
        .catch(error => console.error('Error updating cart count:', error));
    }

    // Toast notification function
    function showToast(message, type = 'success') {
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());
        
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };
        
        const icons = {
            success: '✓',
            error: '✗',
            info: 'ℹ'
        };
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3 transform transition-all duration-300`;
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(100px)';
        
        toast.innerHTML = `
            <span class="text-2xl font-bold">${icons[type]}</span>
            <span class="font-semibold">${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(100px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize Add to Cart functionality
    function initializeAddToCart() {
        const addToCartForms = document.querySelectorAll('.add-to-cart-form');
        
        addToCartForms.forEach(form => {
            // Skip if already has listener
            if (form.dataset.listenerAttached === 'true') {
                return;
            }
            form.dataset.listenerAttached = 'true';
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const button = this.querySelector('button[type="submit"]');
                if (!button) return;
                
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                
                if (!productId) {
                    console.error('Product ID not found');
                    return;
                }
                
                const originalContent = button.innerHTML;
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                 this.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    showToast('Security token missing. Please refresh the page.', 'error');
                    return;
                }
                
                // Check if user is authenticated
                @guest
                    showToast('Please sign in to add items to cart', 'info');
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 1500);
                    return;
                @endguest
                
                // Disable button and show loading
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Adding...</span>
                `;
                
                // Make AJAX request
                fetch(`/cart/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        quantity: 1
                    }),
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success feedback
                        button.innerHTML = `
                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Added!</span>
                        `;
                        
                        // Update cart count
                        updateCartCount();
                        
                        // Show toast notification
                        showToast(`${productName} added to cart!`, 'success');
                        
                        // Small delay to ensure server has processed the cart update
                        setTimeout(() => {
                            // Open cart sidebar - it will refresh the cart content internally before opening
                            if (typeof openCartSidebar === 'function') {
                                openCartSidebar();
                            }
                        }, 100);
                        
                        // Reset button after 2 seconds
                        setTimeout(() => {
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }, 2000);
                    } else {
                        showToast(data.message || 'Failed to add product', 'error');
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Add to cart error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
            });
        });
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeAddToCart();
    });
})();
</script>
@endpush
@endsection