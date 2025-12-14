{{-- resources/views/shared/footer.blade.php --}}
<footer class="bg-gradient-to-b from-gray-800 to-gray-900 text-white">
    {{-- Top: newsletter --}}
    <div class="border-b border-gray-700/50 bg-gradient-to-r from-gray-800 via-gray-750 to-gray-800">
        <div class="container mx-auto px-4 py-16">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                {{-- Logo with glow effect --}}
                <div class="flex flex-col items-start transform transition-transform hover:scale-105">
                    <div class="relative">
                        <div class="absolute inset-0 bg-blue-500/20 blur-xl rounded-full"></div>
                        <img src="/logo.png" alt="logoreal.png" class="mb-2 relative z-10 drop-shadow-2xl" width="180" height="60">
                    </div>
                </div>

                {{-- Newsletter with enhanced styling --}}
                <form action="#" method="POST" class="flex-1 max-w-2xl w-full">
                    @csrf
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full opacity-75 group-hover:opacity-100 blur transition duration-300"></div>
                        <div class="relative flex items-center bg-white rounded-full overflow-hidden shadow-2xl">
                            <input type="email" name="email" placeholder="Enter your email address"
                                class="flex-1 px-6 py-4 text-gray-700 outline-none text-base placeholder-gray-400 focus:placeholder-gray-500 transition-colors" required>
                            <button type="submit"
                                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold px-8 py-4 transition-all duration-300 whitespace-nowrap shadow-lg hover:shadow-green-500/50">
                                Subscribe Now 🔔
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Social icons with enhanced hover effects --}}
                <div class="flex items-center gap-3">
                    <a href="https://twitter.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-gradient-to-br hover:from-orange-400 hover:to-orange-500 transition-all duration-300 transform hover:scale-110 hover:shadow-lg hover:shadow-orange-500/50 group">
                        <span class="group-hover:scale-110 transition-transform">T</span>
                    </a>
                    <a href="https://facebook.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-gradient-to-br hover:from-orange-400 hover:to-orange-500 transition-all duration-300 transform hover:scale-110 hover:shadow-lg hover:shadow-orange-500/50 group">
                        <span class="group-hover:scale-110 transition-transform">f</span>
                    </a>
                    <a href="https://youtube.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-gradient-to-br hover:from-orange-400 hover:to-orange-500 transition-all duration-300 transform hover:scale-110 hover:shadow-lg hover:shadow-orange-500/50 group">
                        <span class="group-hover:scale-110 transition-transform">▶</span>
                    </a>
                    <a href="https://linkedin.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-gradient-to-br hover:from-orange-400 hover:to-orange-500 transition-all duration-300 transform hover:scale-110 hover:shadow-lg hover:shadow-orange-500/50 group">
                        <span class="group-hover:scale-110 transition-transform">in</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main footer content --}}
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            {{-- Column 1: Why People Like Us --}}
            <div class="space-y-4">
                <h3 class="text-white text-xl font-bold mb-6 relative inline-block">
                    Why People Like us!
                    <span class="absolute bottom-0 left-0 w-12 h-1 bg-gradient-to-r from-orange-400 to-green-500 rounded-full"></span>
                </h3>
                <p class="text-gray-300 leading-relaxed mb-6">
                    We provide high-quality home decor at affordable prices.
                </p>
                <a href="/about" class="inline-block">
                    <button class="border-2 border-orange-400 text-white px-8 py-2.5 rounded-full hover:bg-gradient-to-r hover:from-orange-400 hover:to-orange-500 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-orange-500/30">
                        Read More
                    </button>
                </a>
            </div>

            {{-- Column 2: Shop info --}}
            <div>
                <h3 class="text-white text-xl font-bold mb-6 relative inline-block">
                    Shop Info
                    <span class="absolute bottom-0 left-0 w-12 h-1 bg-gradient-to-r from-orange-400 to-green-500 rounded-full"></span>
                </h3>
                <ul class="space-y-3">
                    <li><a href="/about" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">About Us</a></li>
                    <li><a href="/contact" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Contact Us</a></li>
                    <li><a href="/privacy" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Privacy Policy</a></li>
                    <li><a href="/terms" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Terms & Condition</a></li>
                    <li><a href="/return-policy" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Return Policy</a></li>
                    <li><a href="/faqs" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">FAQs & Help</a></li>
                </ul>
            </div>

            {{-- Column 3: Account --}}
            <div>
                <h3 class="text-white text-xl font-bold mb-6 relative inline-block">
                    Account
                    <span class="absolute bottom-0 left-0 w-12 h-1 bg-gradient-to-r from-orange-400 to-green-500 rounded-full"></span>
                </h3>
                <ul class="space-y-3">
                    <li><a href="/profile" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">My Account</a></li>
                    <li><a href="/" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Shop Details</a></li>
                    <li><a href="/cart" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Shopping Cart</a></li>
                    <li><a href="/wishlist" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Wishlist</a></li>
                    <li><a href="/orders" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">Order History</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-orange-400 transition-colors duration-200 hover:translate-x-1 inline-block transform">International Orders</a></li>
                </ul>
            </div>

            {{-- Column 4: Contact --}}
            <div>
                <h3 class="text-white text-xl font-bold mb-6 relative inline-block">
                    Contact
                    <span class="absolute bottom-0 left-0 w-12 h-1 bg-gradient-to-r from-orange-400 to-green-500 rounded-full"></span>
                </h3>
                <div class="space-y-4 text-gray-300">
                    <p class="flex items-start gap-2">
                        <span class="font-semibold text-orange-400 min-w-fit">Address:</span>
                        <span>1429 Netus Rd, NY 48247</span>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="font-semibold text-orange-400 min-w-fit">Email:</span>
                        <span>Example@gmail.com</span>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="font-semibold text-orange-400 min-w-fit">Phone:</span>
                        <span></span>
                    </p>
                    <div>
                        <p class="font-semibold mb-3 text-orange-400">Payment Accepted</p>
                        <div class="flex items-center gap-2 flex-wrap">
                            <img src="/payment-visa.png" class="h-8 hover:scale-110 transition-transform duration-200 cursor-pointer" alt="Visa" />
                            <img src="/payment-mastercard.png" class="h-8 hover:scale-110 transition-transform duration-200 cursor-pointer" alt="Mastercard" />
                            <img src="/payment-maestro.png" class="h-8 hover:scale-110 transition-transform duration-200 cursor-pointer" alt="Maestro" />
                            <img src="/payment-paypal.png" class="h-8 hover:scale-110 transition-transform duration-200 cursor-pointer" alt="PayPal" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom with gradient border --}}
    <div class="border-t border-gray-700/50 bg-gradient-to-r from-gray-900 via-gray-850 to-gray-900">
        <div class="container mx-auto px-4 py-6">
            <p class="text-sm text-gray-400 text-center">
                © <a href="/" class="text-green-500 hover:text-green-400 transition-colors duration-200 font-semibold">AquaEleganceDecorStore</a>, All rights reserved.
            </p>
        </div>
    </div>
</footer>