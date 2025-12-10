{{-- Cart Sidebar Component - Include this in your main layout (layouts/store.blade.php) --}}
{{-- Place it just before closing </body> tag --}}

@php
    $cart = [];
    $subtotal = 0;
    
    if(auth()->check()) {
        // Get cart from database for authenticated users
        $cartProducts = auth()->user()->cartProducts()->withPivot('quantity')->get();
        
        foreach($cartProducts as $product) {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'image' => $product->image_url ?? '/flower.png',
                'qty' => $product->pivot->quantity,
            ];
            $subtotal += $product->price * $product->pivot->quantity;
        }
    } else {
        // Fallback to session cart for guests
        $cart = session()->get('cart', []);
        foreach($cart as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['qty'] ?? 0);
        }
    }
    
    $shipping = $subtotal >= 50 ? 0 : 10;
    $tax = $subtotal * 0.10;
    $total = $subtotal ;
@endphp

<div id="cartSidebar" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div id="cartBackdrop" class="absolute inset-0 bg-black bg-opacity-50 transition-opacity duration-300 opacity-0"></div>
    
    {{-- Sidebar --}}
    <div id="cartPanel" class="absolute right-0 top-0 h-full w-full sm:w-96 bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300">
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-white">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Your Cart (<span id="cartItemCount">{{ count($cart) }}</span>)
            </h2>
            <button onclick="closeCartSidebar()" class="p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Close cart">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-4" id="cartItemsContainer">
            @if(empty($cart) || count($cart) === 0)
                {{-- Empty Cart State --}}
                <div class="h-full flex flex-col items-center justify-center text-center p-4" id="emptyCartState">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
                    <button onclick="closeCartSidebar()" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2.5 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all font-semibold">
                        Continue Shopping
                    </button>
                </div>
            @else
                {{-- Cart Items List --}}
                <div class="space-y-4" id="cartItemsList">
                    @foreach($cart as $id => $item)
                        <div class="flex gap-4 pb-4 border-b border-gray-100 cart-item" data-item-id="{{ $id }}" data-price="{{ $item['price'] }}" data-qty="{{ $item['qty'] }}">
                            {{-- Product Image --}}
                            <a href="{{ route('products.show', $item['slug']) }}" onclick="closeCartSidebar()" class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 hover:opacity-80 transition-opacity">
                                <img src="{{ $item['image'] ?? '/flower.png' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </a>
                            
                            {{-- Product Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <a href="{{ route('products.show', $item['slug']) }}" onclick="closeCartSidebar()" class="font-semibold text-gray-900 hover:text-green-600 transition-colors text-sm line-clamp-2">
                                        {{ $item['name'] }}
                                    </a>
                                    @if(auth()->check())
                                        {{-- Database cart - use API --}}
                                        <button onclick="removeFromCart({{ $id }})" class="text-gray-400 hover:text-red-500 transition-colors ml-2 remove-item-btn" aria-label="Remove item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        {{-- Session cart - use form --}}
                                        <button onclick="removeFromCartSession({{ $id }})" class="text-gray-400 hover:text-red-500 transition-colors ml-2 remove-item-btn" aria-label="Remove item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                
                                <p class="text-sm text-gray-500 mb-3">${{ number_format($item['price'], 2) }}</p>
                                
                                <div class="flex justify-between items-center">
                                    {{-- Quantity Controls --}}
                                    <div class="flex items-center border border-gray-200 rounded-lg">
                                        <button onclick="updateCartQuantity('{{ $id }}', -1)" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors {{ $item['qty'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item['qty'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <span class="px-3 py-1 text-sm font-medium text-gray-700 min-w-[2rem] text-center quantity-display">{{ $item['qty'] }}</span>
                                        <button onclick="updateCartQuantity('{{ $id }}', 1)" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    {{-- Item Total --}}
                                    <p class="font-bold text-gray-900 item-total">${{ number_format($item['price'] * $item['qty'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        {{-- Footer with Totals --}}
        @if(!empty($cart) && count($cart) > 0)
            <div class="border-t border-gray-200 p-4 bg-gray-50" id="cartFooter">
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-semibold" id="cartSubtotal">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Shipping</span>
                        <span class="font-semibold" id="cartShipping">
                            @if($shipping == 0)
                                <span class="text-green-600">FREE</span>
                            @else
                                ${{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Tax (10%)</span>
                        <span class="font-semibold" id="cartTax">${{ number_format($tax, 2) }}</span>
                    </div>
                </div>
                
                <div class="flex justify-between mb-6 text-lg font-bold text-gray-900 pt-3 border-t border-gray-200">
                    <span>Total</span>
                    <span id="cartTotal">${{ number_format($total, 2) }}</span>
                </div>
                
                <a href="/checkout" onclick="closeCartSidebar()" class="block w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold text-center transition-all shadow-md hover:shadow-lg mb-2">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Proceed to Checkout
                    </span>
                </a>
                
                <button onclick="closeCartSidebar()" class="w-full border-2 border-gray-300 bg-white hover:bg-gray-50 text-gray-800 py-2.5 rounded-lg font-semibold transition-colors">
                    Continue Shopping
                </button>
            </div>
        @endif
    </div>
</div>

<style>
/* Cart Sidebar Styles */
#cartSidebar.show #cartBackdrop {
    opacity: 1;
}

#cartSidebar.show #cartPanel {
    transform: translateX(0);
}

body.cart-open {
    overflow: hidden;
}

.cart-item.removing {
    opacity: 0;
    transform: translateX(100px);
    transition: all 0.3s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#emptyCartState {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>

<script>
// Auth status for JavaScript
const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

// Function to refresh cart sidebar content
function refreshCartSidebar() {
    fetch('/cart/items', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        const cart = data.cart || [];
        const cartCount = data.count || 0;
        const subtotal = data.subtotal || 0;
        const shipping = data.shipping || 0;
        const tax = data.tax || 0;
        const total = data.total || 0;
        
        // Update cart count in sidebar header
        const cartItemCountEl = document.getElementById('cartItemCount');
        if (cartItemCountEl) {
            cartItemCountEl.textContent = cartCount;
        }
        
        const container = document.getElementById('cartItemsContainer');
        const footer = document.getElementById('cartFooter');
        
        if (cartCount === 0) {
            // Show empty state
            showEmptyCartState();
        } else {
            // Build cart items HTML
            let itemsHTML = '<div class="space-y-4" id="cartItemsList">';
            
            cart.forEach(item => {
                const itemTotal = (item.price * item.qty).toFixed(2);
                itemsHTML += `
                    <div class="flex gap-4 pb-4 border-b border-gray-100 cart-item" data-item-id="${item.id}" data-price="${item.price}" data-qty="${item.qty}">
                        <a href="/products/${item.slug}" onclick="closeCartSidebar()" class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 hover:opacity-80 transition-opacity">
                            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                        </a>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <a href="/products/${item.slug}" onclick="closeCartSidebar()" class="font-semibold text-gray-900 hover:text-green-600 transition-colors text-sm line-clamp-2">
                                    ${item.name}
                                </a>
                                ${isAuthenticated ? 
                                    `<button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-500 transition-colors ml-2 remove-item-btn" aria-label="Remove item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>` : 
                                    `<button onclick="removeFromCartSession(${item.id})" class="text-gray-400 hover:text-red-500 transition-colors ml-2 remove-item-btn" aria-label="Remove item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>`
                                }
                            </div>
                            <p class="text-sm text-gray-500 mb-3">$${item.price.toFixed(2)}</p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center border border-gray-200 rounded-lg">
                                    <button onclick="updateCartQuantity('${item.id}', -1)" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors ${item.qty <= 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${item.qty <= 1 ? 'disabled' : ''}>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <span class="px-3 py-1 text-sm font-medium text-gray-700 min-w-[2rem] text-center quantity-display">${item.qty}</span>
                                    <button onclick="updateCartQuantity('${item.id}', 1)" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                                <p class="font-bold text-gray-900 item-total">$${itemTotal}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            itemsHTML += '</div>';
            container.innerHTML = itemsHTML;
            
            // Update or create footer
            if (!footer) {
                const footerHTML = `
                    <div class="border-t border-gray-200 p-4 bg-gray-50" id="cartFooter">
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-semibold" id="cartSubtotal">$${subtotal.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Shipping</span>
                                <span class="font-semibold" id="cartShipping">
                                    ${shipping === 0 ? '<span class="text-green-600">FREE</span>' : '$' + shipping.toFixed(2)}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Tax (10%)</span>
                                <span class="font-semibold" id="cartTax">$${tax.toFixed(2)}</span>
                            </div>
                        </div>
                        <div class="flex justify-between mb-6 text-lg font-bold text-gray-900 pt-3 border-t border-gray-200">
                            <span>Total</span>
                            <span id="cartTotal">$${total.toFixed(2)}</span>
                        </div>
                        <a href="/checkout" onclick="closeCartSidebar()" class="block w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold text-center transition-all shadow-md hover:shadow-lg mb-2">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Proceed to Checkout
                            </span>
                        </a>
                        <button onclick="closeCartSidebar()" class="w-full border-2 border-gray-300 bg-white hover:bg-gray-50 text-gray-800 py-2.5 rounded-lg font-semibold transition-colors">
                            Continue Shopping
                        </button>
                    </div>
                `;
                const panel = document.getElementById('cartPanel');
                panel.insertAdjacentHTML('beforeend', footerHTML);
            } else {
                document.getElementById('cartSubtotal').textContent = '$' + subtotal.toFixed(2);
                const shippingEl = document.getElementById('cartShipping');
                if (shipping === 0) {
                    shippingEl.innerHTML = '<span class="text-green-600">FREE</span>';
                } else {
                    shippingEl.textContent = '$' + shipping.toFixed(2);
                }
                document.getElementById('cartTax').textContent = '$' + tax.toFixed(2);
                document.getElementById('cartTotal').textContent = '$' + total.toFixed(2);
            }
        }
    })
    .catch(error => {
        console.error('Error refreshing cart:', error);
    });
}

// Cart Sidebar Functions
function openCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    
    if (!sidebar) {
        console.error('Cart sidebar element not found');
        return;
    }
    
    // Refresh cart content before opening (if sidebar is already open, refresh it)
    if (sidebar.classList.contains('show')) {
        refreshCartSidebar();
        return;
    }
    
    sidebar.classList.remove('hidden');
    document.body.classList.add('cart-open');
    
    // Trigger animation
    setTimeout(() => {
        sidebar.classList.add('show');
    }, 10);
}

function closeCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    
    if (!sidebar) return;
    
    sidebar.classList.remove('show');
    document.body.classList.remove('cart-open');
    
    setTimeout(() => {
        sidebar.classList.add('hidden');
    }, 300);
}

// Calculate and update cart totals
function updateCartTotals() {
    let subtotal = 0;
    const cartItems = document.querySelectorAll('.cart-item');
    
    cartItems.forEach(item => {
        const price = parseFloat(item.dataset.price);
        const qty = parseInt(item.dataset.qty);
        subtotal += price * qty;
    });
    
    const shipping = subtotal >= 50 ? 0 : 10;
    const tax = subtotal * 0.10;
    const total = subtotal + shipping + tax;
    
    // Update DOM
    document.getElementById('cartSubtotal').textContent = '$' + subtotal.toFixed(2);
    
    const shippingEl = document.getElementById('cartShipping');
    if (shipping === 0) {
        shippingEl.innerHTML = '<span class="text-green-600">FREE</span>';
    } else {
        shippingEl.textContent = '$' + shipping.toFixed(2);
    }
    
    document.getElementById('cartTax').textContent = '$' + tax.toFixed(2);
    document.getElementById('cartTotal').textContent = '$' + total.toFixed(2);
    
    // Update cart count
    document.getElementById('cartItemCount').textContent = cartItems.length;
    
    // Update header cart badge if exists
    const headerBadge = document.querySelector('.cart-count');
    if (headerBadge) {
        headerBadge.textContent = cartItems.length;
        if (cartItems.length === 0) {
            headerBadge.classList.add('hidden');
        }
    }
}

// Show empty cart state
function showEmptyCartState() {
    const container = document.getElementById('cartItemsContainer');
    const footer = document.getElementById('cartFooter');
    
    container.innerHTML = `
        <div class="h-full flex flex-col items-center justify-center text-center p-4" id="emptyCartState">
            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Your cart is empty</h3>
            <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
            <button onclick="closeCartSidebar()" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-2.5 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all font-semibold">
                Continue Shopping
            </button>
        </div>
    `;
    
    if (footer) {
        footer.remove();
    }
    
    // Update cart count to 0
    document.getElementById('cartItemCount').textContent = '0';
    
    // Update header cart badge
    const headerBadge = document.querySelector('.cart-count');
    if (headerBadge) {
        headerBadge.textContent = '0';
        headerBadge.classList.add('hidden');
    }
}

// Updated function for quantity updates
function updateCartQuantity(itemId, change) {
    const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
    if (!cartItem) return;
    
    const currentQty = parseInt(cartItem.dataset.qty);
    const newQty = currentQty + change;
    
    if (newQty < 1) return;
    
    // Update UI immediately for better UX
    const qtyDisplay = cartItem.querySelector('.quantity-display');
    const price = parseFloat(cartItem.dataset.price);
    const itemTotal = cartItem.querySelector('.item-total');
    
    qtyDisplay.textContent = newQty;
    cartItem.dataset.qty = newQty;
    itemTotal.textContent = '$' + (price * newQty).toFixed(2);
    
    // Update totals
    updateCartTotals();
    
    // Update decrease button state
    const decreaseBtn = cartItem.querySelector('button[onclick*="updateCartQuantity"]');
    if (newQty <= 1) {
        decreaseBtn.classList.add('opacity-50', 'cursor-not-allowed');
        decreaseBtn.disabled = true;
    } else {
        decreaseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        decreaseBtn.disabled = false;
    }
    
    @if(auth()->check())
        // For authenticated users - use web route
        const formData = new FormData();
        formData.append('quantity', newQty);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('_method', 'PATCH');
        
        fetch(`/cart/${itemId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update cart');
                // Revert on failure
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
    @else
        // For guests - use session-based update
        const formData = new FormData();
        formData.append('quantity', newQty);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('_method', 'PATCH');
        
        fetch(`/cart/${itemId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update cart');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
    @endif
}

// Remove item from cart (for authenticated users)
function removeFromCart(productId) {
    const cartItem = document.querySelector(`[data-item-id="${productId}"]`);
    if (!cartItem) {
        console.error('Cart item not found:', productId);
        return;
    }
    
    // Check for CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found! Add <meta name="csrf-token" content="{{ csrf_token() }}"> to your layout head section.');
        alert('Security token missing. Please refresh the page.');
        return;
    }
    
    console.log('Removing item:', productId);
    
    // Add removing animation
    cartItem.classList.add('removing');
    
    setTimeout(() => {
        const formData = new FormData();
        formData.append('_token', csrfToken.content);
        formData.append('_method', 'DELETE');
        
        console.log('Sending delete request to:', `/cart/${productId}`);
        
        fetch(`/cart/${productId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Remove the item from DOM
                cartItem.remove();
                
                // Check if cart is empty
                const remainingItems = document.querySelectorAll('.cart-item');
                console.log('Remaining items:', remainingItems.length);
                
                if (remainingItems.length === 0) {
                    showEmptyCartState();
                } else {
                    updateCartTotals();
                }
            } else {
                console.error('Failed to remove item:', data.message);
                cartItem.classList.remove('removing');
                alert(data.message || 'Failed to remove item');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            cartItem.classList.remove('removing');
            alert('An error occurred. Please try again.');
        });
    }, 300);
}

// Remove item from session cart (for guests)
function removeFromCartSession(itemId) {
    const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
    if (!cartItem) {
        console.error('Cart item not found:', itemId);
        return;
    }
    
    // Check for CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found!');
        alert('Security token missing. Please refresh the page.');
        return;
    }
    
    console.log('Removing session item:', itemId);
    
    cartItem.classList.add('removing');
    
    setTimeout(() => {
        const formData = new FormData();
        formData.append('_token', csrfToken.content);
        formData.append('_method', 'DELETE');
        
        console.log('Sending delete request to:', `/cart/${itemId}`);
        
        fetch(`/cart/${itemId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Remove the item from DOM
                cartItem.remove();
                
                // Check if cart is empty
                const remainingItems = document.querySelectorAll('.cart-item');
                console.log('Remaining items:', remainingItems.length);
                
                if (remainingItems.length === 0) {
                    showEmptyCartState();
                } else {
                    updateCartTotals();
                }
            } else {
                console.error('Failed to remove item:', data.message);
                cartItem.classList.remove('removing');
                alert(data.message || 'Failed to remove item');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            cartItem.classList.remove('removing');
            alert('An error occurred. Please try again.');
        });
    }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    // Close sidebar when clicking backdrop
    const backdrop = document.getElementById('cartBackdrop');
    if (backdrop) {
        backdrop.addEventListener('click', closeCartSidebar);
    }
    
    // Close sidebar on ESC key
    document.addEventListener('keydown', function(e) {
        const sidebar = document.getElementById('cartSidebar');
        if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
            closeCartSidebar();
        }
    });
});

// Make functions globally available
window.openCartSidebar = openCartSidebar;
window.closeCartSidebar = closeCartSidebar;
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.removeFromCartSession = removeFromCartSession;
window.refreshCartSidebar = refreshCartSidebar;
</script>