{{-- resources/views/shop/partials/features-section.blade.php --}}
@php
    $features = [
        ['title' => 'Free Shipping',     'description' => 'Free on order over $300'],
        ['title' => 'Security Payment',  'description' => '100% security payment'],
        ['title' => '30 Day Return',     'description' => '30 day money guarantee'],
        ['title' => '24/7 Support',      'description' => 'Support every time fast'],
    ];
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($features as $feature)
                <div class="bg-gray-50 rounded-lg p-8 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div class="w-28 h-28 bg-yellow-400 rounded-t-full flex items-center justify-center relative">
                                {{-- simple icon placeholder --}}
                                <span class="text-3xl text-white">★</span>
                                <div
                                    class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full"
                                    style="
                                        width: 0;
                                        height: 0;
                                        border-left: 14px solid transparent;
                                        border-right: 14px solid transparent;
                                        border-top: 14px solid #FBBF24;
                                    "
                                ></div>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        {{ $feature['title'] }}
                    </h3>
                    <p class="text-gray-600 text-sm">
                        {{ $feature['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
