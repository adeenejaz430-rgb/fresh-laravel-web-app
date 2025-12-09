{{-- resources/views/shop/partials/promo-cards-section.blade.php --}}
@php
    $promoCards = [
        [
            'id'         => 1,
            'image'      => '/product.jpg',
            'title'      => 'Collection',
            'discount'   => '20% OFF',
            'bgColor'    => 'bg-yellow-400',
            'labelBg'    => 'bg-green-500',
            'borderColor'=> 'border-yellow-400',
            'link'       => route('products.index', ['category' => 'fountains']),
        ],
        [
            'id'         => 2,
            'image'      => '/earbuds.jpg',
            'title'      => 'Earbuds',
            'discount'   => 'Free delivery',
            'bgColor'    => 'bg-gray-600',
            'labelBg'    => 'bg-white',
            'borderColor'=> 'border-gray-600',
            'link'       => route('products.index', ['category' => 'flowers']),
        ],
        [
            'id'         => 3,
            'image'      => '/headphones.jpg',
            'title'      => 'Headphones',
            'discount'   => 'Discount 30$',
            'bgColor'    => 'bg-green-500',
            'labelBg'    => 'bg-yellow-400',
            'borderColor'=> 'border-green-500',
            'link'       => route('products.index', ['category' => 'plants']),
        ],
    ];
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($promoCards as $card)
                <a href="{{ $card['link'] }}">
                    <div class="group relative overflow-hidden rounded-3xl border-4 border-white shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer">
                        {{-- Image --}}
                        <div class="relative h-64 bg-white overflow-hidden">
                            <img
                                src="{{ $card['image'] }}"
                                alt="{{ $card['title'] }}"
                                class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500"
                            >
                            <div class="absolute top-0 left-0 right-0 h-1 {{ $card['borderColor'] }}"></div>
                        </div>
                        {{-- Floating label card --}}
                        <div class="absolute top-[55%] left-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                            <div class="{{ $card['labelBg'] }} rounded-2xl px-8 py-6 shadow-xl text-center min-w-[280px]">
                                <p class="text-sm font-semibold mb-1 {{ $card['labelBg'] === 'bg-white' ? 'text-gray-600' : 'text-white' }}">
                                    {{ $card['title'] }}
                                </p>
                                <p class="text-2xl font-bold
                                    {{ $card['labelBg'] === 'bg-white' ? 'text-gray-800'
                                       : ($card['labelBg'] === 'bg-yellow-400' ? 'text-gray-800' : 'text-white') }}
                                ">
                                    {{ $card['discount'] }}
                                </p>
                            </div>
                        </div>
                        {{-- Bottom colored --}}
                        <div class="h-40 {{ $card['bgColor'] }}"></div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
