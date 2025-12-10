@extends('layouts.store')

@section('title', 'Contact Us - Artificial Flowers & Plants Store')

@section('content')
<div class="bg-gray-50 min-h-screen">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl md:text-2xl text-green-100">We'd love to hear from you! Get in touch with us.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Contact Form --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Send us a Message</h2>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Your Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                            placeholder="John Doe"
                        />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Your Email *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                            placeholder="john@example.com"
                        />
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject *</label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            value="{{ old('subject') }}"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                            placeholder="How can we help you?"
                        />
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Your Message *</label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all resize-none"
                            placeholder="Tell us about your inquiry..."
                        >{{ old('message') }}</textarea>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg"
                    >
                        Send Message
                    </button>
                </form>
            </div>

            {{-- Contact Information --}}
            <div class="space-y-6">
                {{-- Info Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Get in Touch</h2>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1">Email Us</h3>
                                <p class="text-gray-600">info@artificialflowers.com</p>
                                <p class="text-gray-600">support@artificialflowers.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1">Call Us</h3>
                                <p class="text-gray-600">+1 (555) 123-4567</p>
                                <p class="text-gray-600">Mon - Fri: 9:00 AM - 6:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-1">Visit Us</h3>
                                <p class="text-gray-600">123 Garden Street</p>
                                <p class="text-gray-600">Flower City, FC 12345</p>
                                <p class="text-gray-600">United States</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Business Hours --}}
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Business Hours</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Monday - Friday</span>
                            <span class="font-semibold text-gray-800">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Saturday</span>
                            <span class="font-semibold text-gray-800">10:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Sunday</span>
                            <span class="font-semibold text-gray-800">Closed</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-8 text-white">
                    <h3 class="text-2xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('products.index') }}" class="hover:underline">Browse Products</a></li>
                        <li><a href="{{ route('shop.home') }}" class="hover:underline">Home</a></li>
                        <li><a href="#" class="hover:underline">Shipping Information</a></li>
                        <li><a href="#" class="hover:underline">Returns & Exchanges</a></li>
                        <li><a href="#" class="hover:underline">FAQ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

