{{-- resources/views/shop/partials/hero-section.blade.php --}}
<section class="relative bg-yellow-400  overflow-hidden">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center justify-between min-h-[500px] lg:min-h-[600px] py-12 lg:py-0">
            {{-- Left content --}}
            <div class="w-full lg:w-1/2 text-center lg:text-left mb-8 lg:mb-0 z-10">
                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-white mb-4 leading-tight">
                    Mobile Accessories Collection
                </h1>
                <h2 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-700 mb-6 leading-tight">
                    in Our Store
                </h2>
                <p class="text-gray-700 text-base md:text-lg lg:text-xl max-w-xl mx-auto lg:mx-0 mb-8 leading-relaxed">
                    The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc.
                </p>
                <a href="{{ route('products.index') }}">
                    <button class="bg-green-500 text-white font-bold text-lg px-12 py-4 rounded-full transition-all duration-300 uppercase tracking-wider">
                        BUY
                    </button>
                </a>
            </div>

            {{-- Right image --}}
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end relative">
                <div class="relative w-full max-w-md lg:max-w-lg xl:max-w-xl">
                    {{-- Price tag --}}
                    <div class="absolute -top-4 left-1/2 lg:left-auto lg:right-12 transform -translate-x-1/2 lg:translate-x-0 z-20 bg-white rounded-full shadow-2xl p-4 w-28 h-28 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-gray-800">50$</span>
                    </div>

                    {{-- Product image --}}
                    <div class="relative w-full h-[400px] lg:h-[500px] rounded-b-3xl">
                        <img
                            src="/product.jpg"
                            alt="Product"
                            class="object-contain drop-shadow-2xl rounded-2xl w-full h-full"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Decorative overlay --}}
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-black/5 to-transparent pointer-events-none"></div>
</section>
