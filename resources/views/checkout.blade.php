{{-- resources/views/checkout.blade.php --}}
@extends('layouts.store')

@section('title', 'Checkout')

@php
    $cart = [];
    $subtotal   = 0;
    $itemsCount = 0;

    if (auth()->check()) {
        $cartProducts = auth()->user()->cartProducts()->withPivot('quantity')->get();

        foreach ($cartProducts as $product) {
            $qty   = $product->pivot->quantity;
            $price = $product->price;

            $cart[] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'slug'  => $product->slug,
                'price' => $price,
                'image' => $product->image_url ?? '/flower.png',
                'qty'   => $qty,
            ];

            $itemsCount += $qty;
            $subtotal   += $price * $qty;
        }
    } else {
        $sessionCart = session()->get('cart', []);

        foreach ($sessionCart as $item) {
            $qty   = $item['qty']   ?? 0;
            $price = $item['price'] ?? 0;

            $cart[] = [
                'id'    => $item['id'],
                'name'  => $item['name'],
                'slug'  => $item['slug'],
                'price' => $price,
                'image' => $item['image'] ?? '/flower.png',
                'qty'   => $qty,
            ];

            $itemsCount += $qty;
            $subtotal   += $price * $qty;
        }
    }

    $tax   = $subtotal * 0.10;
    $total = $subtotal + $tax;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <form id="checkout-form">
                        @csrf

                        <!-- Steps Header -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col items-center">
                                    <div id="step-1-icon" class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white transition-colors">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="text-sm mt-2 font-medium">Contact</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div id="step-2-icon" class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-500 transition-colors">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <span class="text-sm mt-2 font-medium">Payment</span>
                                </div>
                            </div>
                            <div class="relative mt-4">
                                <div class="absolute top-0 left-0 h-1 bg-gray-200 w-full rounded-full"></div>
                                <div id="progress-bar" class="absolute top-0 left-0 h-1 bg-blue-600 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span id="error-text"></span>
                        </div>

                        <!-- Success Message -->
                        <div id="success-message" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6 flex items-center justify-center">
                            <i class="fas fa-check w-5 h-5 mr-2"></i>
                            <span class="font-medium">Payment successful! Redirecting...</span>
                        </div>

                        <!-- Step 1: Contact & Address -->
                        <div id="step-1" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" id="name" required placeholder="John Doe"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email" id="email" required placeholder="john@example.com"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Street Address *</label>
                                <input type="text" name="address" id="address" required placeholder="123 Main St"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                    <input type="text" name="city" id="city" required placeholder="New York"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                                    <input type="text" name="state" id="state" required placeholder="NY"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code *</label>
                                    <input type="text" name="zip" id="zip" required placeholder="10001"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                    <select name="country" id="country"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                        <option value="PK">Pakistan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Payment -->
                        <div id="step-2" class="hidden space-y-6">
                            <div>
                                <h3 class="text-lg font-medium mb-4">Payment Information</h3>
                                <div class="border border-gray-300 rounded-lg p-4 bg-white">
                                    <div id="card-element"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Your payment information is encrypted and secure.
                                </p>
                            </div>

                            <div class="border-t pt-4">
                                <h3 class="text-lg font-medium mb-4">Order Summary</h3>
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">
                                            Subtotal (<span id="items-count-payment">{{ $itemsCount }}</span> items)
                                        </span>
                                        <span id="subtotal-payment">${{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tax (10%)</span>
                                        <span>${{ number_format($tax, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                        <span>Total</span>
                                        <span id="total-payment">${{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-8 flex justify-between">
                            <button type="button" id="back-btn"
                                    class="hidden px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                Back
                            </button>
                            <button type="submit" id="submit-btn"
                                    class="ml-auto px-6 py-3 rounded-md text-white flex items-center font-medium transition-colors bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg">
                                <span id="btn-text">Continue</span>
                                <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

                    <div id="cart-items" class="max-h-80 overflow-y-auto mb-4 space-y-3">
                        @forelse($cart as $item)
                            <div class="flex gap-4 pb-4 border-b border-gray-100">
                                <a href="{{ route('products.show', $item['slug']) }}"
                                   class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 hover:opacity-80 transition-opacity">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                         class="w-full h-full object-cover">
                                </a>

                                <div class="flex-1">
                                    <p class="font-semibold text-sm text-gray-900 line-clamp-2">
                                        {{ $item['name'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Qty: {{ $item['qty'] }}</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        ${{ number_format($item['price'] * $item['qty'], 2) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Your cart is empty.</p>
                        @endforelse
                    </div>

                    <div class="space-y-2 pt-4 border-t">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="subtotal-sidebar">
                                ${{ number_format($subtotal, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax (10%)</span>
                            <span class="font-medium">
                                ${{ number_format($tax, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-3 border-t">
                            <span>Total</span>
                            <span class="text-blue-600" id="total-sidebar">
                                ${{ number_format($total, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    {{-- Expose cart to JS for Stripe items array --}}
    <script>
        window.checkoutItems = @json($cart);
    </script>
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush
@endsection
