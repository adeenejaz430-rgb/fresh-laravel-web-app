@extends('layouts.store')

@section('title', 'Blog - Artificial Flowers & Plants')

@section('content')
<div class="bg-gray-50 min-h-screen">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4">Our Blog</h1>
            <p class="text-xl md:text-2xl text-green-100">Tips, Ideas & Inspiration for Your Home & Garden</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Blog Post 1 --}}
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-64 bg-gradient-to-br from-pink-200 via-purple-200 to-green-200 overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-6xl">🌸</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-3">
                            <span class="text-sm text-gray-500">March 15, 2024</span>
                            <span class="text-sm text-green-600 font-semibold">Artificial Flowers</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-3">The Ultimate Guide to Choosing Artificial Flowers for Your Home</h2>
                        <p class="text-gray-600 mb-4">
                            Discover how to select the perfect artificial flowers that look natural and last forever. Learn about different materials, 
                            color coordination, and placement tips to create stunning arrangements that enhance your living space.
                        </p>
                        <a href="#" class="text-green-600 font-semibold hover:text-green-700 inline-flex items-center gap-2">
                            Read More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>

                {{-- Blog Post 2 --}}
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-64 bg-gradient-to-br from-blue-200 via-teal-200 to-green-200 overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-6xl">🌿</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-3">
                            <span class="text-sm text-gray-500">March 10, 2024</span>
                            <span class="text-sm text-green-600 font-semibold">Indoor Plants</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-3">Indoor Water Fountains: Creating Serenity in Your Space</h2>
                        <p class="text-gray-600 mb-4">
                            Transform your home or office with the calming sounds of flowing water. Explore different styles of indoor water fountains, 
                            from modern minimalist designs to traditional tabletop fountains, and learn how to maintain them for years of enjoyment.
                        </p>
                        <a href="#" class="text-green-600 font-semibold hover:text-green-700 inline-flex items-center gap-2">
                            Read More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>

                {{-- Blog Post 3 --}}
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-64 bg-gradient-to-br from-yellow-200 via-orange-200 to-pink-200 overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-6xl">🏺</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-3">
                            <span class="text-sm text-gray-500">March 5, 2024</span>
                            <span class="text-sm text-green-600 font-semibold">Vases & Decor</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-3">Styling Tips: Choosing the Perfect Vase for Your Arrangements</h2>
                        <p class="text-gray-600 mb-4">
                            A beautiful vase can make or break your floral arrangement. Learn about different vase shapes, sizes, and materials, 
                            and discover how to match them with your artificial flowers and plants for maximum visual impact.
                        </p>
                        <a href="#" class="text-green-600 font-semibold hover:text-green-700 inline-flex items-center gap-2">
                            Read More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>

                {{-- Blog Post 4 --}}
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-64 bg-gradient-to-br from-green-200 via-emerald-200 to-teal-200 overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-6xl">🌱</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-3">
                            <span class="text-sm text-gray-500">February 28, 2024</span>
                            <span class="text-sm text-green-600 font-semibold">Care Tips</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-3">Maintaining Your Artificial Plants: A Complete Care Guide</h2>
                        <p class="text-gray-600 mb-4">
                            While artificial plants don't need water or sunlight, they do require proper care to maintain their beauty. 
                            Learn simple cleaning techniques, dusting methods, and storage tips to keep your artificial plants looking fresh and vibrant for years.
                        </p>
                        <a href="#" class="text-green-600 font-semibold hover:text-green-700 inline-flex items-center gap-2">
                            Read More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Categories --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Categories</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition-colors">Artificial Flowers</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition-colors">Indoor Plants</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition-colors">Vases & Decor</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition-colors">Water Fountains</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition-colors">Care Tips</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition-colors">Styling Ideas</a></li>
                    </ul>
                </div>

                {{-- Recent Posts --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Posts</h3>
                    <ul class="space-y-4">
                        <li>
                            <a href="#" class="text-gray-800 hover:text-green-600 transition-colors font-semibold">
                                The Ultimate Guide to Choosing Artificial Flowers
                            </a>
                            <p class="text-sm text-gray-500 mt-1">March 15, 2024</p>
                        </li>
                        <li>
                            <a href="#" class="text-gray-800 hover:text-green-600 transition-colors font-semibold">
                                Indoor Water Fountains: Creating Serenity
                            </a>
                            <p class="text-sm text-gray-500 mt-1">March 10, 2024</p>
                        </li>
                        <li>
                            <a href="#" class="text-gray-800 hover:text-green-600 transition-colors font-semibold">
                                Styling Tips: Choosing the Perfect Vase
                            </a>
                            <p class="text-sm text-gray-500 mt-1">March 5, 2024</p>
                        </li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-xl font-bold mb-3">Subscribe to Our Newsletter</h3>
                    <p class="text-green-100 mb-4">Get the latest tips, trends, and exclusive offers delivered to your inbox.</p>
                    <form class="space-y-3">
                        <input 
                            type="email" 
                            placeholder="Your email address" 
                            class="w-full px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white"
                        />
                        <button 
                            type="submit" 
                            class="w-full bg-white text-green-600 font-bold py-3 rounded-lg hover:bg-green-50 transition-colors"
                        >
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


