@extends('layouts.store')

@section('title', 'Home')

@section('content')
<div class="w-full">
    {{-- HERO SECTION - 3-Image Slider (FauxNatural Style) --}}
    {{-- 
        INSTRUCTIONS TO ADD YOUR IMAGES:
        1. Place your 3 hero images in: storage/app/public/products/
        2. Name them: hs1.jpg, hs2.jpg, hs3.jpg
        3. Supported formats: .jpg, .jpeg, .png, .webp
        4. Recommended size: 1920x1080px or larger for best quality
        5. After adding images, run: php artisan storage:link (if not already done)
    --}}
    <section class="relative w-full" style="min-height: 80vh;">
        <div id="hero-slider" class="relative w-full" style="min-height: 80vh; height: 80vh;">
            {{-- Background Image Slides --}}
            <div class="hero-slide-item absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out" data-index="0" style="opacity: 1; z-index: 1;">
                <img 
                    src="{{ asset('storage/products/hs1.jpg') }}" 
                    alt="Beautiful Home Decor"
                    class="w-full h-full object-cover"
                    style="width: 100%; height: 100%; object-fit: cover; object-position: center;"
                    onerror="this.style.display='none';"
                />
            </div>
            
            <div class="hero-slide-item absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out" data-index="1" style="opacity: 0; z-index: 0;">
                <img 
                    src="{{ asset('storage/products/hs2.jpg') }}" 
                    alt="Elegant Interior Design"
                    class="w-full h-full object-cover"
                    style="width: 100%; height: 100%; object-fit: cover; object-position: center;"
                    onerror="this.style.display='none';"
                />
            </div>
            
            <div class="hero-slide-item absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out" data-index="2" style="opacity: 0; z-index: 0;">
                <img 
                    src="{{ asset('storage/products/hs5.jpg') }}" 
                    alt="Stylish Home Accessories"
                    class="w-full h-full object-cover"
                    style="width: 100%; height: 100%; object-fit: cover; object-position: center;"
                    onerror="this.style.display='none';"
                />
            </div>

            {{-- Dark Overlay for Text Readability --}}
            <div class="absolute inset-0 bg-black/35 z-10"></div>

            {{-- Navigation Arrows --}}
            <button 
                id="hero-prev-btn"
                class="absolute left-6 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 hover:scale-110"
                aria-label="Previous slide"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button 
                id="hero-next-btn"
                class="absolute right-6 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 hover:scale-110"
                aria-label="Next slide"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            {{-- Slider Dots Navigation --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-30">
                <button class="hero-dot w-2.5 h-2.5 rounded-full bg-white transition-all duration-300 hover:scale-125" data-dot="0" aria-label="Slide 1"></button>
                <button class="hero-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/80 transition-all duration-300 hover:scale-125" data-dot="1" aria-label="Slide 2"></button>
                <button class="hero-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/80 transition-all duration-300 hover:scale-125" data-dot="2" aria-label="Slide 3"></button>
            </div>

            {{-- Content Container - Centered Text with Per-Slide Captions --}}
            <div class="relative z-20 container mx-auto px-4 md:px-6 lg:px-8 h-full">
                <div class="flex flex-col items-center justify-center h-full">
                    <div class="w-full max-w-4xl text-center">
                        {{-- Slide 1 Caption --}}
                        <div class="hero-caption absolute inset-0 flex flex-col items-center justify-center transition-opacity duration-700 ease-in-out" data-caption="0" style="opacity: 1;">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-cinzel text-white mb-6 leading-tight">
                                A Season of Style:<br>
                                <span class="font-normal">Discover Our Premium Collection</span>
                            </h1>
                            
                            <a href="{{ route('products.index') }}"
                               class="inline-block mt-8 px-8 py-3 bg-white text-gray-900 font-cinzel font-semibold uppercase tracking-wide text-sm hover:bg-gray-100 transition-all duration-300 border border-gray-300">
                                Shop Now
                            </a>
                        </div>

                        {{-- Slide 2 Caption --}}
                        <div class="hero-caption absolute inset-0 flex flex-col items-center justify-center transition-opacity duration-700 ease-in-out" data-caption="1" style="opacity: 0;">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-cinzel text-white mb-6 leading-tight">
                                Elegant Artificial Flowers:<br>
                                <span class="font-normal">Transform Your Space with Beauty</span>
                            </h1>
                            
                            <a href="{{ route('products.index') }}?category=flowers"
                               class="inline-block mt-8 px-8 py-3 bg-white text-gray-900 font-cinzel font-semibold uppercase tracking-wide text-sm hover:bg-gray-100 transition-all duration-300 border border-gray-300">
                                Explore Flowers
                            </a>
                        </div>

                        {{-- Slide 3 Caption --}}
                        <div class="hero-caption absolute inset-0 flex flex-col items-center justify-center transition-opacity duration-700 ease-in-out" data-caption="2" style="opacity: 0;">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-cinzel text-white mb-6 leading-tight">
                                Premium Home Decor:<br>
                                <span class="font-normal">Vases, Plants & Water Fountains</span>
                            </h1>
                            
                            <a href="{{ route('products.index') }}"
                               class="inline-block mt-8 px-8 py-3 bg-white text-gray-900 font-cinzel font-semibold uppercase tracking-wide text-sm hover:bg-gray-100 transition-all duration-300 border border-gray-300">
                                View Collection
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Hero Slider JavaScript --}}
    <script>
    (function() {
        'use strict';
        
        document.addEventListener('DOMContentLoaded', function() {
            let currentSlide = 0;
            const totalSlides = 3;
            let autoSlideInterval;
            
            const slides = document.querySelectorAll('.hero-slide-item');
            const dots = document.querySelectorAll('.hero-dot');
            const prevBtn = document.getElementById('hero-prev-btn');
            const nextBtn = document.getElementById('hero-next-btn');
            const sliderContainer = document.getElementById('hero-slider');
            
            if (!slides.length) return;
            
            function showSlide(index) {
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                
                currentSlide = index;
                
                // Update slides
                slides.forEach((slide, i) => {
                    if (i === currentSlide) {
                        slide.style.opacity = '1';
                        slide.style.zIndex = '1';
                    } else {
                        slide.style.opacity = '0';
                        slide.style.zIndex = '0';
                    }
                });
                
                // Update captions
                const captions = document.querySelectorAll('.hero-caption');
                captions.forEach((caption, i) => {
                    if (i === currentSlide) {
                        caption.style.opacity = '1';
                        caption.style.zIndex = '2';
                    } else {
                        caption.style.opacity = '0';
                        caption.style.zIndex = '1';
                    }
                });
                
                // Update dots
                dots.forEach((dot, i) => {
                    if (i === currentSlide) {
                        dot.classList.remove('bg-white/50', 'bg-white/80');
                        dot.classList.add('bg-white');
                    } else {
                        dot.classList.remove('bg-white');
                        dot.classList.add('bg-white/50');
                    }
                });
            }
            
            function nextSlide() {
                showSlide(currentSlide + 1);
            }
            
            function prevSlide() {
                showSlide(currentSlide - 1);
            }
            
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 5000);
            }
            
            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                }
            }
            
            // Navigation button events
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    stopAutoSlide();
                    startAutoSlide();
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    stopAutoSlide();
                    startAutoSlide();
                });
            }
            
            // Dot navigation
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                    stopAutoSlide();
                    startAutoSlide();
                });
            });
            
            // Pause on hover
            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', stopAutoSlide);
                sliderContainer.addEventListener('mouseleave', startAutoSlide);
            }
            
            // Initialize
            showSlide(0);
            startAutoSlide();
        });
    })();
    </script>

    {{-- Hero Slider Styles --}}
    <style>
    #hero-slider {
        position: relative;
    }
    
    .hero-slide-item {
        will-change: opacity;
    }
    
    .hero-slide-item img {
        min-width: 100%;
        min-height: 100%;
    }
    
    #hero-prev-btn,
    #hero-next-btn {
        transition: all 0.3s ease;
    }
    
    #hero-prev-btn:hover,
    #hero-next-btn:hover {
        transform: translateY(-50%) scale(1.1);
    }
    
    .hero-dot {
        cursor: pointer;
    }
    
    @media (max-width: 640px) {
        #hero-prev-btn,
        #hero-next-btn {
            padding: 0.5rem;
        }
        
        #hero-prev-btn svg,
        #hero-next-btn svg {
            width: 1.25rem;
            height: 1.25rem;
        }
        
        #hero-prev-btn {
            left: 1rem;
        }
        
        #hero-next-btn {
            right: 1rem;
        }
    }
    </style>

    {{-- FEATURED PRODUCTS --}}
    @include('shop.partials.featured-products')

    {{-- MIDDLE SECTIONS FROM YOUR DESIGN (only render if they exist) --}}
    @includeIf('shop.partials.section-mid')
    @includeIf('shop.partials.section-last')

    {{-- RECENT PRODUCTS CAROUSEL --}}
    @includeIf('shop.partials.recent-products')

    {{-- HERO SECTION WITH CONTENT - Moved Below Recent Products --}}
    <section class="relative w-full bg-white py-16">
        {{-- Hero slider container with content --}}
        <div class="hero-slider-container relative h-[500px] md:h-[600px] lg:h-[700px] overflow-hidden bg-gray-50 rounded-2xl mx-4 md:mx-8 lg:mx-auto max-w-7xl">
            {{-- 3-Image Slider --}}
            <div class="hero-slides-wrapper relative w-full h-full">
                {{-- Slide 1 --}}
                <div class="hero-slide absolute inset-0 w-full h-full opacity-100 transition-opacity duration-700 ease-in-out" data-slide="0">
                    <img 
                        src="{{ asset('storage/products/hs1.jpg') }}" 
                        alt="Premium Mobile Technology"
                        class="w-full h-full object-cover"
                </div>
                
                {{-- Slide 2 --}}
                <div class="hero-slide absolute inset-0 w-full h-full opacity-0 transition-opacity duration-700 ease-in-out" data-slide="1">
                    <img 
                        src="{{ asset('storage/products/hs2.jpg') }}" 
                        alt="Smartphones & Accessories"
                        class="w-full h-full object-cover"
                        
                    />
                </div>
                
                {{-- Slide 3 --}}
                <div class="hero-slide absolute inset-0 w-full h-full opacity-0 transition-opacity duration-700 ease-in-out" data-slide="2">
                    <img 
                        src="{{ asset('storage/products/hs3.jpg') }}" 
                        alt="Latest Mobile Devices"
                        class="w-full h-full object-cover"
                        
                    />
                </div>
            </div>

            {{-- Content Overlay (Text Content) --}}
            <div class="absolute inset-0 flex items-center z-10">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="max-w-2xl">
                        {{-- Badge/Tag --}}
                        <div class="inline-flex items-center gap-2 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full mb-6 shadow-md">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-sm font-semibold text-gray-800">Premium Technology</span>
                        </div>

                        {{-- Main Headline --}}
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-gray-900 leading-tight mb-6">
                            <span class="block mb-2">Experience Out-Edge</span>
                            <span class="block text-green-600">Mobile Technology</span>
                            <span class="block text-2xl sm:text-3xl md:text-4xl lg:text-5xl mt-3 text-gray-700 font-bold">
                                Smartphones &amp; Accessories for Every Lifestyle
                            </span>
                        </h1>

                        {{-- Description --}}
                        <p class="text-base sm:text-lg md:text-xl text-gray-700 mb-8 leading-relaxed max-w-xl">
                            Explore the latest smartphones, premium cases, chargers, and accessories designed to keep
                            you connected and stylish. Quality products, unbeatable prices, and fast delivery.
                        </p>

                        {{-- CTA Button --}}
                        <a
                            href="{{ route('products.index') }}"
                            class="group inline-flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-lg text-base sm:text-lg uppercase transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                        >
                            <span>Explore Now</span>
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Navigation Arrows --}}
            <button 
                class="hero-nav-btn hero-nav-prev absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 hover:opacity-100"
                aria-label="Previous slide"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button 
                class="hero-nav-btn hero-nav-next absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 hover:opacity-100"
                aria-label="Next slide"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            {{-- Slider Dots Navigation --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                <button class="hero-dot w-3 h-3 rounded-full bg-white w-8 transition-all duration-300 shadow-md" data-dot="0" aria-label="Slide 1"></button>
                <button class="hero-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all duration-300 shadow-md" data-dot="1" aria-label="Slide 2"></button>
                <button class="hero-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all duration-300 shadow-md" data-dot="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </section>

    {{-- Hero Slider JavaScript (Content Section) --}}
    <script>
    (function() {
        'use strict';
        
        // Hero section with content 3-image slider
        let currentSlide = 0;
        const totalSlides = 3;
        let autoSlideInterval;
        
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        const prevBtn = document.querySelector('.hero-nav-prev');
        const nextBtn = document.querySelector('.hero-nav-next');
        const sliderContainer = document.querySelector('.hero-slider-container');
        
        // Show specific slide
        function showSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            
            currentSlide = index;
            
            slides.forEach((slide, i) => {
                if (i === currentSlide) {
                    slide.classList.remove('opacity-0');
                    slide.classList.add('opacity-100');
                } else {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0');
                }
            });
            
            dots.forEach((dot, i) => {
                if (i === currentSlide) {
                    dot.classList.remove('bg-white/50', 'bg-white/80');
                    dot.classList.add('bg-white', 'w-8');
                } else {
                    dot.classList.remove('bg-white', 'w-8');
                    dot.classList.add('bg-white/50');
                }
            });
        }
        
        function nextSlide() {
            showSlide(currentSlide + 1);
        }
        
        function prevSlide() {
            showSlide(currentSlide - 1);
        }
        
        function startAutoSlide() {
            autoSlideInterval = setInterval(nextSlide, 5000);
        }
        
        function stopAutoSlide() {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
            }
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                stopAutoSlide();
                startAutoSlide();
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                stopAutoSlide();
                startAutoSlide();
            });
        }
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                stopAutoSlide();
                startAutoSlide();
            });
        });
        
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', stopAutoSlide);
            sliderContainer.addEventListener('mouseleave', startAutoSlide);
            sliderContainer.addEventListener('mouseenter', () => {
                if (prevBtn) prevBtn.classList.add('opacity-100');
                if (nextBtn) nextBtn.classList.add('opacity-100');
            });
            sliderContainer.addEventListener('mouseleave', () => {
                if (prevBtn) prevBtn.classList.remove('opacity-100');
                if (nextBtn) nextBtn.classList.remove('opacity-100');
            });
        }
        
        if (slides.length > 0) {
            showSlide(0);
            startAutoSlide();
        }
    })();
    </script>

    {{-- Hero Slider Styles (Content Section) --}}
    <style>
    .hero-slider-container {
        position: relative;
    }
    
    .hero-slide {
        will-change: opacity;
    }
    
    .hero-slide.opacity-100 {
        z-index: 1;
    }
    
    .hero-slide.opacity-0 {
        z-index: 0;
    }
    
    .hero-nav-btn {
        transition: opacity 0.3s ease, transform 0.2s ease;
    }
    
    .hero-nav-btn:hover {
        transform: translateY(-50%) scale(1.1);
    }
    
    .hero-dot {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .hero-dot:hover {
        transform: scale(1.2);
    }
    
    @media (max-width: 640px) {
        .hero-nav-btn {
            opacity: 1 !important;
            padding: 0.5rem;
        }
        
        .hero-nav-btn svg {
            width: 1.25rem;
            height: 1.25rem;
        }
    }
    </style>

    {{-- PROMO SECTION --}}
    @includeIf('shop.partials.promo-section')

    {{-- FEATURES SECTION - Moved to bottom above footer --}}
    @includeIf('shop.partials.features-section')
</div>
@endsection