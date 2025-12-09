{{-- resources/views/shared/footer.blade.php --}}
<footer class="bg-gray-700 text-white">
    {{-- Top: newsletter --}}
    <div class="border-b border-gray-600">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                {{-- Logo --}}
                <div class="flex flex-col items-start">
                    <img src="/logoreal1.png" alt="Mobile Accessories Logo" class="mb-2" width="180" height="60">
                </div>

                {{-- Newsletter --}}
                <form action="#" method="POST" class="flex-1 max-w-2xl w-full">
                    @csrf
                    <div class="flex items-center bg-white rounded-full overflow-hidden shadow-lg">
                        <input type="email" name="email" placeholder="Your Email"
                            class="flex-1 px-6 py-4 text-gray-700 outline-none text-base" required>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-4 transition-colors whitespace-nowrap">
                            Subscribe Now
                        </button>
                    </div>
                </form>

                {{-- Social icons --}}
                <div class="flex items-center gap-3">
                    <a href="https://twitter.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-orange-400">
                        T
                    </a>
                    <a href="https://facebook.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-orange-400">
                        f
                    </a>
                    <a href="https://youtube.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-orange-400">
                        ▶
                    </a>
                    <a href="https://linkedin.com" target="_blank"
                        class="w-12 h-12 border-2 border-orange-400 rounded-full flex items-center justify-center hover:bg-orange-400">
                        in
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main footer content --}}
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            {{-- Column 1: Why People Like Us --}}
            <div>
                <h3 class="text-white text-xl font-bold mb-6">Why People Like us!</h3>
                <p class="text-gray-300 leading-relaxed mb-6">
                    We provide high-quality mobile accessories at affordable prices.
                </p>
                <a href="/about">
                    <button class="border-2 border-orange-400 text-white px-8 py-2 rounded-full hover:bg-orange-400">
                        Read More
                    </button>
                </a>
            </div>

            {{-- Column 2: Shop info --}}
            <div>
                <h3 class="text-white text-xl font-bold mb-6">Shop Info</h3>
                <ul class="space-y-3">
                    <li><a href="/about" class="text-gray-300 hover:text-orange-400">About Us</a></li>
                    <li><a href="/contact" class="text-gray-300 hover:text-orange-400">Contact Us</a></li>
                    <li><a href="/privacy" class="text-gray-300 hover:text-orange-400">Privacy Policy</a></li>
                    <li><a href="/terms" class="text-gray-300 hover:text-orange-400">Terms & Condition</a></li>
                    <li><a href="/return-policy" class="text-gray-300 hover:text-orange-400">Return Policy</a></li>
                    <li><a href="/faqs" class="text-gray-300 hover:text-orange-400">FAQs & Help</a></li>
                </ul>
            </div>

            {{-- Column 3: Account --}}
            <div>
                <h3 class="text-white text-xl font-bold mb-6">Account</h3>
                <ul class="space-y-3">
                    <li><a href="/profile" class="text-gray-300 hover:text-orange-400">My Account</a></li>
                    <li><a href="/" class="text-gray-300 hover:text-orange-400">Shop Details</a></li>
                    <li><a href="/cart" class="text-gray-300 hover:text-orange-400">Shopping Cart</a></li>
                    <li><a href="/wishlist" class="text-gray-300 hover:text-orange-400">Wishlist</a></li>
                    <li><a href="/orders" class="text-gray-300 hover:text-orange-400">Order History</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-orange-400">International Orders</a></li>
                </ul>
            </div>

            {{-- Column 4: Contact --}}
            <div>
                <h3 class="text-white text-xl font-bold mb-6">Contact</h3>
                <div class="space-y-4 text-gray-300">
                    <p><span class="font-semibold">Address:</span> 1429 Netus Rd, NY 48247</p>
                    <p><span class="font-semibold">Email:</span> Example@gmail.com</p>
                    <p><span class="font-semibold">Phone:</span> +0123 4567 8910</p>
                    <div>
                        <p class="font-semibold mb-3">Payment Accepted</p>
                        <div class="flex items-center gap-2">
                            <img src="/payment-visa.png" class="h-8" />
                            <img src="/payment-mastercard.png" class="h-8" />
                            <img src="/payment-maestro.png" class="h-8" />
                            <img src="/payment-paypal.png" class="h-8" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom --}}
    <div class="border-t border-gray-600">
        <div class="container mx-auto px-4 py-6">
            <p class="text-sm text-gray-400 text-center">
                © <a href="/" class="text-green-500 hover:text-green-400">Mobile Phone Store</a>, All rights reserved.
            </p>
        </div>
    </div>
</footer>
