<!-- {{-- resources/views/shop/partials/featured-collection-section.blade.php --}}
@php
    $featuredProduct = [
        'id'         => 1,
        'image'      => '/product.jpg',
        'title'      => 'Premium Mobile Accessories',
        'description'=> 'Discover our curated collection of premium mobile accessories designed for the modern lifestyle. Quality meets elegance.',
        'price'      => '50',
        'currency'   => '$',
        'link'       => route('products.index', ['category' => 'mobile-accessories']),
    ];
@endphp

<section class="relative py-20 overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50">
    {{-- Decorative background elements --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-200/20 rounded-full blur-3xl -z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-teal-200/20 rounded-full blur-3xl -z-0"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center max-w-7xl mx-auto">
            
            {{-- Left Column: Content --}}
            <div class="space-y-6 lg:pr-8">
                {{-- Label Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full border border-emerald-200/50 shadow-sm">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-emerald-700">Featured Collection</span>
                </div>
                
                {{-- Main Title --}}
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                    {{ $featuredProduct['title'] }}
                </h2>
                
                {{-- Description --}}
                <p class="text-lg text-gray-600 leading-relaxed max-w-xl">
                    {{ $featuredProduct['description'] }}
                </p>
                
                {{-- CTA Button --}}
                <div class="pt-4">
                    <a href="{{ $featuredProduct['link'] }}" 
                       class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-full shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:scale-105 transition-all duration-300 group">
                        <span>Shop Collection</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
                
                {{-- Trust Indicators --}}
                <div class="flex flex-wrap gap-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-600 font-medium">Free Shipping</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-600 font-medium">Premium Quality</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-600 font-medium">Easy Returns</span>
                    </div>
                </div>
            </div>
            
            {{-- Right Column: Product Showcase --}}
            <div class="relative">
                {{-- Main Product Card --}}
                <div class="relative bg-white/70 backdrop-blur-sm rounded-3xl p-8 shadow-2xl shadow-gray-900/10 border border-white/50">
                    {{-- Price Badge --}}
                    <div class="absolute -top-6 -right-6 z-20">
                        <div class="relative">
                            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full p-1 shadow-xl shadow-emerald-500/40">
                                <div class="bg-white rounded-full px-6 py-4 min-w-[120px] text-center">
                                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Starting</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ $featuredProduct['currency'] }}{{ $featuredProduct['price'] }}</p>
                                </div>
                            </div>
                            {{-- Pulse animation ring --}}
                            <div class="absolute inset-0 bg-emerald-400/30 rounded-full animate-ping"></div>
                        </div>
                    </div>
                    
                    {{-- Product Image Container --}}
                    <div class="relative aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                        <img 
                            src="{{ $featuredProduct['image'] }}" 
                            alt="{{ $featuredProduct['title'] }}"
                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-700"
                        >
                        
                        {{-- Decorative corner accents --}}
                        <div class="absolute top-4 left-4 w-8 h-8 border-t-2 border-l-2 border-emerald-400/40 rounded-tl-lg"></div>
                        <div class="absolute bottom-4 right-4 w-8 h-8 border-b-2 border-r-2 border-emerald-400/40 rounded-br-lg"></div>
                    </div>
                    
                    {{-- Bottom accent bar --}}
                    <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-500 rounded-b-3xl"></div>
                </div>
                
                {{-- Floating decorative elements --}}
                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-gradient-to-br from-emerald-400/20 to-teal-400/20 rounded-full blur-2xl"></div>
                <div class="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-teal-400/20 to-emerald-400/20 rounded-full blur-2xl"></div>
            </div>
            
        </div>
    </div>
</section>

{{-- Promo Cards Section --}}
@php
    $promoCards = [
        [
            'id'         => 1,
            'image'      => '/product.jpg',
            'title'      => 'Premium Cases',
            'discount'   => '20% OFF',
            'bgGradient' => 'from-amber-400 to-orange-500',
            'link'       => route('products.index', ['category' => 'cases']),
        ],
        [
            'id'         => 2,
            'image'      => '/earbuds.jpg',
            'title'      => 'Wireless Earbuds',
            'discount'   => 'Free Delivery',
            'bgGradient' => 'from-slate-600 to-slate-800',
            'link'       => route('products.index', ['category' => 'audio']),
        ],
        [
            'id'         => 3,
            'image'      => '/headphones.jpg',
            'title'      => 'Headphones',
            'discount'   => 'Save $30',
            'bgGradient' => 'from-emerald-500 to-teal-600',
            'link'       => route('products.index', ['category' => 'headphones']),
        ],
    ];
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold text-gray-900 mb-3">Explore More Collections</h3>
            <p class="text-gray-600">Handpicked selections just for you</p>
        </div>
        
        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @foreach($promoCards as $card)
                <a href="{{ $card['link'] }}" class="group">
                    <div class="relative overflow-hidden rounded-2xl bg-white shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        {{-- Image Section --}}
                        <div class="relative h-64 bg-gradient-to-br {{ $card['bgGradient'] }} overflow-hidden">
                            <img
                                src="{{ $card['image'] }}"
                                alt="{{ $card['title'] }}"
                                class="w-full h-full object-cover mix-blend-overlay opacity-90 group-hover:scale-110 group-hover:opacity-100 transition-all duration-700"
                            >
                            
                            {{-- Gradient overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        </div>
                        
                        {{-- Content Section --}}
                        <div class="relative bg-white p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $card['title'] }}</h4>
                                    <p class="text-2xl font-bold bg-gradient-to-r {{ $card['bgGradient'] }} bg-clip-text text-transparent">
                                        {{ $card['discount'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r {{ $card['bgGradient'] }} text-white group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            
                            {{-- Bottom accent line --}}
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r {{ $card['bgGradient'] }} transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section> -->