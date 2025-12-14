{{-- Featured Products Section - Create this as resources/views/components/featured-products.blade.php --}}

@php
    // Get all categories
    $allCategories = App\Models\Category::orderBy('name')->get();
    
    // Get featured products with their category relationships
    $featuredProducts = App\Models\Product::where('featured', true)
        ->with('categoryRelation')
        ->where('quantity', '>', 0) // Only show in-stock products
        ->latest()
        ->get();
    
    // If no featured products, get latest 8 in-stock products
    if ($featuredProducts->isEmpty()) {
        $featuredProducts = App\Models\Product::with('categoryRelation')
            ->where('quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();
    }
@endphp

@if($featuredProducts->isNotEmpty())
<section id="featuredProducts" class="relative py-16 md:py-24 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    {{-- Shimmer Animation Styles --}}
    <style>
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
        .skeleton-box {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
        }
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-8px);
        }
    </style>

    <div class="container mx-auto px-4 md:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 bg-green-100 px-5 py-2 rounded-full mb-4">
                <span class="text-2xl">⭐</span>
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Featured Products</span>
            </div>
            
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">
                Handpicked for You
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Discover our carefully selected collection of premium products
            </p>

            {{-- Dynamic Category Tabs --}}
            <div class="flex flex-wrap justify-center gap-3 mt-8" id="categoryTabs">
                <button 
                    onclick="filterByCategory('All Products')"
                    class="category-tab px-6 py-2.5 rounded-full font-medium text-sm md:text-base transition-all duration-300 bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg"
                    data-category="All Products"
                >
                    All Products
                </button>
                
                @foreach($allCategories as $category)
                    <button 
                        onclick="filterByCategory('{{ $category->name }}')"
                        class="category-tab px-6 py-2.5 rounded-full font-medium text-sm md:text-base transition-all duration-300 bg-white text-gray-600 hover:bg-gray-100 border-2 border-green-200 hover:border-green-400 hover:shadow-md"
                        data-category="{{ $category->name }}"
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Loading State (Hidden by default, shown via JS) --}}
        <div id="loadingState" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @for($i = 0; $i < 8; $i++)
                <div class="bg-white border-2 border-gray-100 rounded-2xl overflow-hidden animate-pulse">
                    <div class="h-56 skeleton-box animate-shimmer"></div>
                    <div class="p-5 space-y-3">
                        <div class="h-6 skeleton-box animate-shimmer rounded-lg w-3/4"></div>
                        <div class="space-y-2">
                            <div class="h-4 skeleton-box animate-shimmer rounded"></div>
                            <div class="h-4 skeleton-box animate-shimmer rounded w-5/6"></div>
                        </div>
                        <div class="flex items-center justify-between pt-3">
                            <div class="h-8 skeleton-box animate-shimmer rounded-lg w-24"></div>
                            <div class="h-10 skeleton-box animate-shimmer rounded-full w-32"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        {{-- Empty State (Hidden by default, shown via JS) --}}
        <div id="emptyState" class="hidden flex flex-col items-center justify-center py-20 px-4">
            <div class="relative mb-8">
                {{-- Animated circles --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 bg-green-100 rounded-full animate-ping opacity-20"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-green-200 rounded-full animate-pulse"></div>
                </div>
                
                {{-- Icon --}}
                <div class="relative w-32 h-32 bg-gradient-to-br from-green-50 to-green-100 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>

            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 text-center">
                No Products in This Category
            </h3>
            <p class="text-gray-500 text-center mb-8 max-w-md">
                We couldn't find any featured products in this category. Try selecting a different category.
            </p>
            
            <button onclick="filterByCategory('All Products')" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-8 py-4 rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                Show All Products
            </button>
        </div>

        {{-- Products Grid --}}
        <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                @php
                    $stockQty = (int)($product->quantity ?? 0);
                    // Use the accessor that properly converts storage path to URL
                    $firstImage = $product->main_image_url;
                    
                    $isWishlisted = false;
                    if(auth()->check()) {
                        $isWishlisted = auth()->user()->wishlistProducts->contains($product->id);
                    }
                @endphp

                <a href="{{ route('products.show', $product->slug) }}" class="product-card bg-white border-2 border-gray-100 rounded-2xl overflow-hidden hover:shadow-2xl hover:border-green-400 flex flex-col group cursor-pointer block" data-category="{{ $product->categoryRelation->name ?? 'Uncategorized' }}">
                    {{-- Image Container --}}
                    <div class="relative h-56 overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                        {{-- Category Badge --}}
                        <div class="absolute top-4 right-4 z-10" onclick="event.stopPropagation();">
                            <span class="bg-green-500 text-white px-4 py-1.5 rounded-full text-xs font-semibold shadow-lg backdrop-blur-sm bg-opacity-90">
                                {{ $product->categoryRelation->name ?? 'Uncategorized' }}
                            </span>
                        </div>

                        {{-- Featured Badge --}}
                        <div class="absolute top-4 left-4 z-10" onclick="event.stopPropagation();">
                            <span class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center gap-1">
                                <span>⭐</span>
                                <span>Featured</span>
                            </span>
                        </div>

                        {{-- Product Image --}}
                        <img
                            src="{{ $firstImage }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            onerror="this.onerror=null;this.src='{{ asset('/flower.png') }}';"
                        />

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-green-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                            {{ $product->name }}
                        </h3>

                        <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-1 min-h-[2.5rem]">
                            {{ $product->description ?? 'Premium quality product with excellent features.' }}
                        </p>

                        {{-- Stock Badge --}}
                        <div class="mb-3">
                            @if($stockQty > 10)
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                    ✓ In Stock ({{ $stockQty }})
                                </span>
                            @elseif($stockQty > 0)
                                <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full">
                                    ⚠ Only {{ $stockQty }} left
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mt-auto pt-4 border-t-2 border-gray-100">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Price</p>
                                <span class="text-2xl font-black bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            </div>

                            <form class="add-to-cart-form" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" onclick="event.stopPropagation();">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button
                                    type="submit"
                                    @if($stockQty === 0) disabled @endif
                                    class="add-cart-btn group/btn px-5 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105
                                        {{ $stockQty === 0 
                                            ? 'bg-gray-300 text-gray-600 cursor-not-allowed' 
                                            : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white' 
                                        }}"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $stockQty === 0 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <span>Browse All Products</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<script>
(function() {
    'use strict';
    
    // Ensure this runs after DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFeaturedProducts);
    } else {
        // DOM already loaded
        initFeaturedProducts();
    }
    
    function initFeaturedProducts() {
        const categoryTabs = document.querySelectorAll('.category-tab');
        const productCards = document.querySelectorAll('.product-card');
        const productsGrid = document.getElementById('productsGrid');
        const emptyState = document.getElementById('emptyState');

    // Category filter function
    window.filterByCategory = function(categoryName) {
        // Update active tab styling
        categoryTabs.forEach(tab => {
            if (tab.dataset.category === categoryName) {
                tab.className = 'category-tab px-6 py-2.5 rounded-full font-medium text-sm md:text-base transition-all duration-300 bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg transform scale-105';
            } else {
                tab.className = 'category-tab px-6 py-2.5 rounded-full font-medium text-sm md:text-base transition-all duration-300 bg-white text-gray-600 hover:bg-gray-100 border-2 border-green-200 hover:border-green-400 hover:shadow-md';
            }
        });

        // Filter products with animation
        let visibleCount = 0;
        productCards.forEach((card, index) => {
            const cardCategory = card.dataset.category;
            if (categoryName === 'All Products' || cardCategory === categoryName) {
                card.style.display = '';
                // Stagger animation
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
                visibleCount++;
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });

        // Show/hide empty state
        setTimeout(() => {
            if (visibleCount === 0) {
                productsGrid.classList.add('hidden');
                emptyState.classList.remove('hidden');
            } else {
                productsGrid.classList.remove('hidden');
                emptyState.classList.add('hidden');
            }
        }, 300);
    };

    // Add to Cart with AJAX - Initialize function
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
                                 document.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    showToast('Security token missing. Please refresh the page.', 'error');
                    return;
                }
                
                // Check if user is authenticated (server-side check will handle this, but we can show a message)
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
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    initializeAddToCart();
    
    // Re-initialize after category filtering (in case DOM changes)
    const originalFilterByCategory = window.filterByCategory;
    window.filterByCategory = function(categoryName) {
        if (originalFilterByCategory) {
            originalFilterByCategory(categoryName);
        }
        // Re-initialize add to cart after filtering
        setTimeout(() => {
            initializeAddToCart();
        }, 350);
    };

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
    } // End of initFeaturedProducts function
})(); // End of IIFE
</script>
@endif
