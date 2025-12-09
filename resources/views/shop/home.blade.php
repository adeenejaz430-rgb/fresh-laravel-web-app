@extends('layouts.store')

@section('title', 'Home')

@section('content')
<div class="w-full">
    {{-- MAIN HERO (big cover banner) --}}
    <section class="relative h-[600px] md:h-[700px] lg:h-[800px] w-full overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="/logo.png
                "
                alt="Experience Cutting-Edge Mobile Technology"
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="relative h-full flex items-center">
            <div class="container mx-auto px-4 md:px-8 lg:px-16">
                <div class="max-w-3xl">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-4">
                        Experience Cutting-Edge Mobile Technology
                        <br>
                        <span class="text-yellow-400">
                            Smartphones &amp; Accessories for Every Lifestyle
                        </span>
                    </h1>

                    <p class="text-base sm:text-lg md:text-xl text-white/90 mb-8 leading-relaxed max-w-2xl">
                        Explore the latest smartphones, premium cases, chargers, and accessories designed to keep
                        you connected and stylish. Quality products, unbeatable prices, and fast delivery.
                    </p>

                    <a
                        href="{{ route('products.index') }}"
                        class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-8 py-4 rounded-md text-base sm:text-lg uppercase transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-1"
                    >
                        Explore Now
                    </a>
                </div>
            </div>
        </div>

        {{-- slider dots (static example) --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
            <button class="w-12 h-3 bg-yellow-400 rounded-full"></button>
            <button class="w-3 h-3 bg-white/50 rounded-full"></button>
            <button class="w-3 h-3 bg-white/50 rounded-full"></button>
        </div>
    </section>

    {{-- OPTIONAL EXTRA YELLOW HERO (Laravel version of your React HeroSection) --}}
    <div class="pt-12">
    @includeIf('shop.partials.hero-section')</div>

    {{-- FEATURED PRODUCTS --}}
@include('shop.partials.featured-products')
    {{-- FEATURES SECTION --}}
    @includeIf('shop.partials.features-section')

    {{-- MIDDLE SECTIONS FROM YOUR DESIGN (only render if they exist) --}}
    @includeIf('shop.partials.section-mid')
    @includeIf('shop.partials.section-last')

    {{-- RECENT PRODUCTS CAROUSEL --}}
    @includeIf('shop.partials.recent-products')

    {{-- PROMO CARDS / COLLECTIONS --}}
    @includeIf('shop.partials.promo-cards-section')
</div>
@endsection
