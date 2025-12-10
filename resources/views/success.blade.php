@extends('layouts.store')

@section('title', 'Order Success')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <!-- Confetti container -->
    <div class="confetti-container fixed inset-0 pointer-events-none"></div>
    
    <!-- Success content -->
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-6 animate-fade-in">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-5xl text-green-500"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
            <p class="text-lg text-gray-600">
                Thank you for your purchase. Your order has been received and is being processed.
            </p>
        </div>
        
        <div class="border-t border-b border-gray-200 py-6 my-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-left">
                    <p class="text-sm text-gray-500 mb-1">Order Number</p>
                    <p class="font-medium" id="order-number">#ORD-0000</p>
                </div>
                <div class="text-left">
                    <p class="text-sm text-gray-500 mb-1">Date</p>
                    <p class="font-medium" id="order-date">{{ date('F j, Y') }}</p>
                </div>
                <div class="text-left">
                    <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                    <p class="font-medium">Credit Card</p>
                </div>
                <div class="text-left">
                    <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                    <p class="font-medium text-blue-600" id="order-total">$0.00</p>
                </div>
            </div>
        </div>
        
        <div class="mb-8">
            <p class="text-gray-600 mb-4">
                We've sent a confirmation email with order details to
                <strong id="order-email"></strong>.
            </p>
            <p class="text-gray-600">
                You can track your order status in your account or use the order number provided above.
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('orders.index') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-list"></i>
                    View My Orders
                </a>
            @else
                <a href="{{ route('orders.track') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search"></i>
                    Track Order
                </a>
            @endauth
            
            <a href="/" class="flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <i class="fas fa-home"></i>
                Return to Home
            </a>
            
            <a href="/products" class="flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <i class="fas fa-shopping-bag"></i>
                Continue Shopping
            </a>
        </div>

        <!-- Order Details Link (if available) -->
        <div id="order-details-link" class="mt-6 hidden">
            <a href="#" id="view-order-link" class="text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-eye mr-2"></i>View Full Order Details
            </a>
        </div>
    </div>
</div>

<style>
@keyframes confetti-fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
    }
    100% {
        transform: translateY(100vh) rotate(360deg);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.confetti {
    position: absolute;
    top: -20px;
    border-radius: 2px;
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create confetti effect
    function createConfetti() {
        const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
        const container = document.querySelector('.confetti-container');
        
        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = `${Math.random() * 100}%`;
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.width = `${Math.random() * 10 + 5}px`;
            confetti.style.height = `${Math.random() * 10 + 5}px`;
            confetti.style.opacity = Math.random();
            confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
            confetti.style.animation = `confetti-fall ${Math.random() * 3 + 2}s linear forwards`;
            confetti.style.animationDelay = `${Math.random() * 2}s`;
            
            container.appendChild(confetti);
        }
    }
    
    // Load order details from sessionStorage (from checkout.js)
    const lastOrderJson = sessionStorage.getItem('lastOrder');
    
    if (lastOrderJson) {
        const order = JSON.parse(lastOrderJson);
        
        // Order number (shortened paymentIntent id)
        if (order.orderNumber) {
            const orderNum = order.orderNumber.includes('_') 
                ? order.orderNumber.substring(order.orderNumber.lastIndexOf('_') + 1, order.orderNumber.lastIndexOf('_') + 5).toUpperCase()
                : order.orderNumber;
            document.getElementById('order-number').textContent = `#ORD-${orderNum}`;
        }
        
        // Date
        if (order.date) {
            const date = new Date(order.date);
            document.getElementById('order-date').textContent = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        // Total
        if (order.total) {
            document.getElementById('order-total').textContent = `$${order.total}`;
        }

        // Email
        if (order.email) {
            document.getElementById('order-email').textContent = order.email;
        }

        // Optionally fetch full order from backend (if webhook already created it)
        if (order.orderNumber) {
            fetchOrderDetails(order.orderNumber);
        }
        
        // Clear after use
        sessionStorage.removeItem('lastOrder');
    }
    
    // Fetch order details from server using payment_intent_id
    async function fetchOrderDetails(paymentIntentId) {
        try {
            const response = await fetch('/order-by-payment-intent', {   // 👈 web route, not /api/...
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntentId
                })
            });

            if (response.ok) {
                const data = await response.json();
                
                // Update with actual order number from database
                if (data.order && data.order.order_number) {
                    document.getElementById('order-number').textContent = data.order.order_number;
                }
                
                // Show link to full order details
                const orderDetailsLink = document.getElementById('order-details-link');
                const viewOrderLink = document.getElementById('view-order-link');
                
                @auth
                    viewOrderLink.href = `/orders/${data.order.order_number}`;
                @else
                    viewOrderLink.href = `/orders/${data.order.order_number}?email=${encodeURIComponent(data.order.email)}`;
                @endauth
                
                orderDetailsLink.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error fetching order details:', error);
        }
    }
    
    // Trigger confetti
    createConfetti();
});
</script>
@endsection
