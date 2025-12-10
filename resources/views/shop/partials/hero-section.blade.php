{{-- resources/views/shop/partials/hero-section.blade.php --}}
<section class="relative bg-gradient-to-br from-yellow-400 via-amber-400 to-yellow-500 overflow-hidden">
    {{-- Decorative Background Patterns --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between min-h-[500px] lg:min-h-[600px] py-16 lg:py-20">
            {{-- Left content --}}
            <div class="w-full lg:w-1/2 text-center lg:text-left mb-12 lg:mb-0 z-10">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-6 border border-white/30">
                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                    <span class="text-sm font-semibold text-gray-800">Featured Collection</span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white mb-4 leading-tight drop-shadow-lg">
                    Mobile Accessories
                    <span class="block text-gray-800">Collection</span>
                </h1>
                <h2 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-gray-800 mb-6 leading-tight">
                    in Our Store
                </h2>
                
                <p class="text-gray-800/90 text-base md:text-lg lg:text-xl max-w-xl mx-auto lg:mx-0 mb-10 leading-relaxed font-medium backdrop-blur-sm bg-white/20 rounded-lg p-5 border border-white/30">
                    The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc.
                </p>
                
                <a href="{{ route('products.index') }}">
                    <button class="group relative bg-gradient-to-r from-teal-500 via-emerald-500 to-green-600 hover:from-teal-600 hover:via-emerald-600 hover:to-green-700 text-white font-bold text-lg px-12 py-5 rounded-full transition-all duration-300 uppercase tracking-wider shadow-2xl hover:shadow-emerald-500/50 transform hover:-translate-y-1 hover:scale-105 overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            BUY
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    </button>
                </a>
            </div>

            {{-- Right image --}}
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end relative">
                <div class="relative w-full max-w-md lg:max-w-lg xl:max-w-xl">
                    {{-- Enhanced Price tag with animation --}}
                    <div class="absolute -top-6 left-1/2 lg:left-auto lg:right-8 transform -translate-x-1/2 lg:translate-x-0 z-20 bg-white rounded-full shadow-2xl p-6 w-32 h-32 flex flex-col items-center justify-center border-4 border-yellow-300 animate-bounce hover:scale-110 transition-transform duration-300">
                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">From</span>
                        <span class="text-3xl font-black text-gray-900">50$</span>
                    </div>

                    {{-- Product image with enhanced styling --}}
                    <div class="relative w-full h-[400px] lg:h-[500px]">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/30 to-transparent rounded-3xl blur-xl"></div>
                        <div class="relative w-full h-full bg-white/20 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-2xl">
                            <img
                                src="/product.jpg"
                                alt="Product"
                                class="object-contain drop-shadow-2xl rounded-2xl w-full h-full transform hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                    </div>

                    {{-- Floating decorative elements --}}
                    <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-white/20 rounded-full blur-2xl"></div>
                    <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/15 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced decorative overlay --}}
    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-white/10 via-white/5 to-transparent pointer-events-none"></div>
    
    {{-- Top decorative border --}}
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
</section>