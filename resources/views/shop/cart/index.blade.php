<!-- @extends('layouts.store')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-3xl font-bold text-gray-800">Shopping Cart</h1>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        @if(empty($cart) || count($cart) === 0)
            {{-- Empty Cart --}}
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <span class="material-symbols-outlined text-gray-300" style="font-size: 120px;">shopping_cart</span>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Your cart is empty</h2>
                <p class="text-gray-600 mt-2">Add some products to get started!</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-6 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $id => $item)
                        <div class="bg-white rounded-2xl shadow-sm p-6 flex gap-6">
                            {{-- Product Image --}}
                            <a href="{{ route('products.show', $item['slug']) }}" class="w-32 h-32 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hover:opacity-80 transition-opacity">
                                <img src="{{ $item['image'] ?? '/flower.png' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </a>

                            {{-- Product Details --}}
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <a href="{{ route('products.show', $item['slug']) }}" class="text-lg font-bold text-gray-800 hover:text-green-600">
                                            {{ $item['name'] }}
                                        </a>
                                        <p class="text-xl font-bold text-gray-800 mt-2">${{ number_format($item['price'], 2) }}</p>
                                    </div>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-sm text-gray-600 font-medium">Qty:</label>
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            value="{{ $item['qty'] }}" 
                                            min="1"
                                            class="w-20 h-10 border-2 border-gray-300 rounded-lg text-center font-bold focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                        >
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                            Update
                                        </button>
                                    </form>
                                    <p class="text-2xl font-bold text-gray-800">${{ number_format($item['price'] * $item['qty'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Clear Cart --}}
                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-700 font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Continue Shopping
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 font-semibold flex items-center gap-2" onclick="return confirm('Are you sure you want to clear your cart?')">
                                <span class="material-symbols-outlined">delete_sweep</span>
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-4">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ count($cart) }} items)</span>
                                <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="font-semibold">
                                    @if($shipping == 0)
                                        <span class="text-green-600">FREE</span>
                                    @else
                                        ${{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>Tax (10%)</span>
                                <span class="font-semibold">${{ number_format($tax, 2) }}</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-xl font-bold text-gray-800">
                                    <span>Total</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        @if($subtotal < 50 && $subtotal > 0)
                            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-800 flex items-start gap-2">
                                <span class="material-symbols-outlined text-base">local_shipping</span>
                                <span>Add <strong>${{ number_format(50 - $subtotal, 2) }}</strong> more for free shipping!</span>
                            </div>
                        @endif

                        <button class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all hover:scale-105 shadow-md flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">shopping_bag</span>
                            Proceed to Checkout
                        </button>

                        <div class="mt-6 space-y-2 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600 text-base">verified</span>
                                <span>Secure checkout</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600 text-base">autorenew</span>
                                <span>Easy returns</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600 text-base">support_agent</span>
                                <span>24/7 customer support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
        <span class="material-symbols-outlined">error</span>
        {{ session('error') }}
    </div>
@endif

<script>
// Auto-hide flash messages after 3 seconds
setTimeout(() => {
    const messages = document.querySelectorAll('.fixed.bottom-4');
    messages.forEach(msg => {
        msg.style.transition = 'opacity 0.3s';
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 300);
    });
}, 3000);
</script>
@endsection -->
@extends('layouts.store')

@section('title', 'Shopping Cart')

@section('content')
{{-- Cart Sidebar --}}
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
                Your Cart (<span id="cartItemCount">{{ count($cart ?? []) }}</span>)
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
                        <div class="flex gap-4 pb-4 border-b border-gray-100 cart-item" data-item-id="{{ $id }}">
                            {{-- Product Image --}}
                            <a href="{{ route('products.show', $item['slug']) }}" class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 hover:opacity-80 transition-opacity">
                                <img src="{{ $item['image'] ?? '/flower.png' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </a>
                            
                            {{-- Product Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <a href="{{ route('products.show', $item['slug']) }}" class="font-semibold text-gray-900 hover:text-green-600 transition-colors text-sm line-clamp-2">
                                        {{ $item['name'] }}
                                    </a>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="remove-item-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors ml-2" aria-label="Remove item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <p class="text-sm text-gray-500 mb-3">${{ number_format($item['price'], 2) }}</p>
                                
                                <div class="flex justify-between items-center">
                                    {{-- Quantity Controls --}}
                                    <div class="flex items-center border border-gray-200 rounded-lg">
                                        <button onclick="updateQuantity('{{ $id }}', {{ $item['qty'] - 1 }})" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors" {{ $item['qty'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <span class="px-3 py-1 text-sm font-medium text-gray-700 min-w-[2rem] text-center quantity-display">{{ $item['qty'] }}</span>
                                        <button onclick="updateQuantity('{{ $id }}', {{ $item['qty'] + 1 }})" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
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
                        <span class="font-semibold" id="cartSubtotal">${{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Shipping</span>
                        <span class="font-semibold" id="cartShipping">
                            @if(($shipping ?? 0) == 0)
                                <span class="text-green-600">FREE</span>
                            @else
                                ${{ number_format($shipping ?? 0, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Tax (10%)</span>
                        <span class="font-semibold" id="cartTax">${{ number_format($tax ?? 0, 2) }}</span>
                    </div>
                </div>
                
                <div class="flex justify-between mb-6 text-lg font-bold text-gray-900 pt-3 border-t border-gray-200">
                    <span>Total</span>
                    <span id="cartTotal">${{ number_format($total ?? 0, 2) }}</span>
                </div>
                
                <button onclick="window.location.href='/checkout'" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold flex items-center justify-center transition-all shadow-md hover:shadow-lg mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Proceed to Checkout
                </button>
                
                <button onclick="closeCartSidebar()" class="w-full border-2 border-gray-300 bg-white hover:bg-gray-50 text-gray-800 py-2.5 rounded-lg font-semibold transition-colors">
                    Continue Shopping
                </button>
            </div>
        @endif
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 flash-message">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 flash-message">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

<style>
/* Smooth animations */
#cartSidebar.show #cartBackdrop {
    opacity: 1;
}

#cartSidebar.show #cartPanel {
    transform: translateX(0);
}

/* Disable body scroll when sidebar is open */
body.cart-open {
    overflow: hidden;
}

/* Smooth item removal animation */
.cart-item.removing {
    opacity: 0;
    transform: translateX(100px);
    transition: all 0.3s ease-out;
}
</style>

<script>
// Open cart sidebar
function openCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    const backdrop = document.getElementById('cartBackdrop');
    const panel = document.getElementById('cartPanel');
    
    sidebar.classList.remove('hidden');
    document.body.classList.add('cart-open');
    
    // Trigger animation
    setTimeout(() => {
        sidebar.classList.add('show');
    }, 10);
}

// Close cart sidebar
function closeCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    const backdrop = document.getElementById('cartBackdrop');
    const panel = document.getElementById('cartPanel');
    
    sidebar.classList.remove('show');
    document.body.classList.remove('cart-open');
    
    setTimeout(() => {
        sidebar.classList.add('hidden');
    }, 300);
}

// Update quantity
function updateQuantity(itemId, newQty) {
    if (newQty < 1) return;
    
    // Create form data
    const formData = new FormData();
    formData.append('quantity', newQty);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PATCH');
    
    // Send AJAX request
    fetch(`/cart/${itemId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            location.reload(); // Simple reload, or you can update DOM elements dynamically
        }
    })
    .catch(error => console.error('Error:', error));
}

// Handle remove item with animation
document.addEventListener('DOMContentLoaded', function() {
    // Setup remove item forms
    document.querySelectorAll('.remove-item-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const cartItem = this.closest('.cart-item');
            cartItem.classList.add('removing');
            
            setTimeout(() => {
                this.submit();
            }, 300);
        });
    });
    
    // Close sidebar when clicking backdrop
    document.getElementById('cartBackdrop')?.addEventListener('click', closeCartSidebar);
    
    // Close sidebar on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('cartSidebar').classList.contains('show')) {
            closeCartSidebar();
        }
    });
    
    // Auto-hide flash messages
    setTimeout(() => {
        const messages = document.querySelectorAll('.flash-message');
        messages.forEach(msg => {
            msg.style.transition = 'opacity 0.3s';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 300);
        });
    }, 3000);
});

// Make openCartSidebar available globally for the navbar
window.openCartSidebar = openCartSidebar;
</script>
@endsection