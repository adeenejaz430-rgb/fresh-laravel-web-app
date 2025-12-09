{{-- resources/views/shop/partials/recent-products.blade.php --}}
@if($recentProducts->isNotEmpty())
<section class="py-16 px-4 bg-white">
    <div class="container mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl text-center md:text-4xl font-bold text-gray-800">
                Recent Products
            </h2>
            {{-- (Optional) arrows: you can wire these with a little JS if you want horizontal scroll --}}
        </div>

        {{-- Products grid (simpler than scroll slider – grid works great on Laravel) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($recentProducts as $product)
                @php
                    $stockQty = (int)($product->quantity ?? 0);
                    $img = $product->images[0] ?? $product->image ?? '/images/placeholder.jpg';
                @endphp
                <div class="bg-white border-2 border-green-200 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col">
                    {{-- Image --}}
                    <div class="relative h-64 bg-gray-50 overflow-hidden">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img
                                src="{{ $img }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                            >
                        </a>
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-500 text-white text-xs font-semibold px-4 py-2 rounded-full">
                                {{ $product->category ?? 'Product' }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">
                            {{ $product->name }}
                        </h3>
                        <p class="text-gray-500 text-sm mb-4 flex-1">
                            {{ $product->description ?? 'Beautiful and high-quality product for your daily use.' }}
                        </p>

                        <div class="flex items-center justify-between mt-auto">
                            <div class="text-2xl font-bold text-gray-800">
                                ${{ number_format($product->price, 2) }}
                            </div>
                            <form
                                method="POST"
                                action="{{ route('cart.add', $product->id) }}"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    @if($stockQty === 0) disabled @endif
                                    class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-medium px-5 py-2.5 rounded-full transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                >
                                    🛒
                                    {{ $stockQty === 0 ? 'Out of Stock' : 'Add to cart' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
