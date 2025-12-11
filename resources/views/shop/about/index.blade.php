@extends('layouts.store')

@section('title', 'About Us - Artificial Flowers & Plants Store')

@section('content')
<div class="bg-gray-50 min-h-screen">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4">About Us</h1>
            <p class="text-xl md:text-2xl text-green-100">Bringing Nature's Beauty to Your Home, Forever</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        {{-- Our Story Section --}}
        <div class="max-w-4xl mx-auto mb-16">
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Our Story</h2>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p class="text-lg leading-relaxed mb-4">
                        Welcome to our online store, where we specialize in bringing the timeless beauty of nature into your home through our exquisite collection of artificial flowers, plants, decorative vases, and elegant mini water fountains.
                    </p>
                    <p class="text-lg leading-relaxed mb-4">
                        Founded with a passion for home decor and a commitment to quality, we understand that not everyone has the time or green thumb to maintain live plants. That's why we've curated a stunning selection of lifelike artificial flowers and plants that require zero maintenance while adding elegance and charm to any space.
                    </p>
                    <p class="text-lg leading-relaxed">
                        Our mission is to help you create beautiful, welcoming environments that reflect your personal style. Whether you're looking to brighten up a corner with vibrant artificial flowers, add sophistication with premium vases, or create a serene atmosphere with a mini water fountain, we have something special for every home.
                    </p>
                </div>
            </div>
        </div>

        {{-- What We Offer Section --}}
        <div class="mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 text-center mb-8">What We Offer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Artificial Flowers --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-5xl mb-4 text-center">🌸</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Artificial Flowers</h3>
                    <p class="text-gray-600 text-center">
                        Lifelike blooms that never wilt, bringing year-round color and beauty to your home.
                    </p>
                </div>

                {{-- Artificial Plants --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-5xl mb-4 text-center">🌿</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Artificial Plants</h3>
                    <p class="text-gray-600 text-center">
                        Realistic greenery that adds life to any room without the need for watering or sunlight.
                    </p>
                </div>

                {{-- Decorative Vases --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-5xl mb-4 text-center">🏺</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Decorative Vases</h3>
                    <p class="text-gray-600 text-center">
                        Elegant vases in various styles and sizes, perfect for displaying your favorite arrangements.
                    </p>
                </div>

                {{-- Mini Water Fountains --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-5xl mb-4 text-center">💧</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Mini Water Fountains</h3>
                    <p class="text-gray-600 text-center">
                        Serene indoor water fountains that create a peaceful, zen-like atmosphere in your space.
                    </p>
                </div>
            </div>
        </div>

        {{-- Why Choose Us Section --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 text-center mb-8">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Premium Quality</h3>
                    <p class="text-gray-600">
                        We carefully select only the highest quality artificial flowers and plants that look incredibly realistic.
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Affordable Prices</h3>
                    <p class="text-gray-600">
                        Beautiful home decor shouldn't break the bank. We offer competitive prices on all our products.
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Fast Shipping</h3>
                    <p class="text-gray-600">
                        We ensure your orders are carefully packaged and shipped quickly so you can start decorating right away.
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Secure Shopping</h3>
                    <p class="text-gray-600">
                        Your privacy and security are important to us. We use secure payment processing for all transactions.
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Customer Satisfaction</h3>
                    <p class="text-gray-600">
                        We're committed to your satisfaction. If you're not happy with your purchase, we'll make it right.
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Zero Maintenance</h3>
                    <p class="text-gray-600">
                        All our artificial flowers and plants require no watering, sunlight, or care—just pure beauty.
                    </p>
                </div>
            </div>
        </div>

        {{-- Our Commitment Section --}}
        <div class="max-w-4xl mx-auto mb-16">
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-lg p-8 md:p-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">Our Commitment</h2>
                <p class="text-lg text-gray-700 leading-relaxed text-center mb-6">
                    We believe that everyone deserves to have a beautiful, welcoming home. Our carefully curated collection of artificial flowers, plants, vases, and mini water fountains allows you to create stunning decor that lasts forever.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed text-center">
                    Whether you're decorating a cozy apartment, a spacious home, or an office space, we're here to help you find the perfect pieces that reflect your style and bring joy to your everyday life.
                </p>
            </div>
        </div>

        {{-- Call to Action --}}
        <div class="text-center">
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Ready to Transform Your Space?</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Explore our collection and discover the perfect pieces for your home
                </p>
                <a 
                    href="{{ route('products.index') }}"
                    class="inline-block bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold px-8 py-4 rounded-lg text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                >
                    Shop Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

