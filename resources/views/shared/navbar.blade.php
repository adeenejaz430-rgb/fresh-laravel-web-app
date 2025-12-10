<!-- <header class="fixed w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-lg">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            {{-- LOGO --}}
            <a href="{{ route('shop.home') }}" class="flex items-center z-50 transform hover:scale-105 transition-transform duration-200">
                <div class="relative h-10 w-32 sm:h-12 sm:w-36 lg:h-16 lg:w-48">
                    <img src="/logo.png" alt="Logo" class="h-full w-full object-contain drop-shadow-md">
                </div>
            </a>

            @php
                use Illuminate\Support\Facades\Route;

                $user      = auth()->user();
                $isAuthed  = (bool) $user;
                $current   = url()->current();

                $navLinks = [
                    ['name' => 'Home',     'route' => 'shop.home',      'href' => '/'],
                    ['name' => 'Products', 'route' => 'products.index', 'href' => '/products'],
                    ['name' => 'About',    'route' => null,             'href' => '/about'],
                    ['name' => 'Blog',     'route' => null,             'href' => '/blog'],
                    ['name' => 'Contact',  'route' => null,             'href' => '/contact'],
                ];

                $categoriesList = $categories ?? [];
                $cartCount      = $cartCount ?? 0;
                $wishlistCount  = $wishlistCount ?? 0;
            @endphp

            {{-- DESKTOP NAV --}}
            <nav class="hidden lg:flex items-center space-x-8">

                {{-- MAIN LINKS --}}
                @foreach($navLinks as $link)
                    @php
                        $href = (!empty($link['route']) && Route::has($link['route']))
                                    ? route($link['route'])
                                    : url($link['href']);

                        $active = ($current === $href);
                    @endphp

                    <a href="{{ $href }}"
                       class="text-base font-semibold transition-all duration-300 relative pb-1 group
                              {{ $active ? 'text-green-600' : 'text-gray-700 hover:text-green-600' }}">
                        {{ $link['name'] }}

                        <span class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-600 
                                     transition-all duration-300 {{ $active ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endforeach

                {{-- CATEGORIES DROPDOWN --}}
                @if(count($categoriesList))
                    <div class="relative" id="categoriesDropdown">
                        <button class="text-base font-semibold text-gray-700 hover:text-green-600 pb-1 
                                       transition-colors duration-300 flex items-center gap-1 group">
                            Categories
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            class="absolute left-0 mt-2 w-56 bg-white shadow-2xl rounded-xl 
                                   transition-all duration-300 border border-gray-100 z-50 hidden
                                   transform origin-top scale-95 opacity-0"
                            id="categoriesMenu"
                            style="transition: opacity 0.3s, transform 0.3s;"
                        >
                            <div class="py-2">
                                @foreach($categoriesList as $cat)
                                    @php
                                        $slug = $cat->slug ?? $cat['slug'];
                                        $categoryHref = Route::has('category.show')
                                            ? route('category.show', $slug)
                                            : '#';
                                    @endphp

                                    <a href="{{ $categoryHref }}"
                                       class="block px-4 py-3 text-sm text-gray-700 
                                              hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 
                                              hover:text-green-600 transition-all duration-200
                                              border-l-4 border-transparent hover:border-green-600">
                                        {{ $cat->name ?? $cat['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </nav>

            {{-- RIGHT SIDE --}}
            <div class="flex items-center space-x-3">

                {{-- SEARCH --}}
                <form action="{{ route('products.index') }}" method="GET"
                      class="hidden lg:flex items-center bg-gray-50 rounded-full overflow-hidden 
                             border-2 border-gray-200 focus-within:border-green-500 
                             transition-all duration-300 hover:shadow-md">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search products..."
                           class="px-4 py-2.5 w-56 bg-transparent focus:outline-none text-sm">
                    <button type="submit" 
                            class="px-4 h-full bg-gradient-to-r from-green-600 to-emerald-600 
                                   text-white hover:from-green-700 hover:to-emerald-700 
                                   transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>

                {{-- WISHLIST --}}
                @if($isAuthed)
                    <a href="{{ route('wishlist.index') }}"
                       class="relative group hidden lg:flex items-center justify-center 
                              w-11 h-11 rounded-full bg-gradient-to-br from-pink-500 to-rose-600
                              text-white hover:shadow-lg hover:scale-110 
                              transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>

                        @if($wishlistCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold
                                         rounded-full h-5 w-5 flex items-center justify-center 
                                         border-2 border-white shadow-lg animate-pulse">
                                {{ $wishlistCount }}
                            </span>
                        @endif

                        {{-- Tooltip --}}
                        <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2
                                     bg-gray-900 text-white text-xs py-1 px-3 rounded-lg
                                     opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                     whitespace-nowrap pointer-events-none">
                            Wishlist
                        </span>
                    </a>
                @endif

                {{-- CART --}}
                @if($isAuthed)
                    <a href="/cart"
                       class="relative group hidden lg:flex items-center justify-center 
                              w-11 h-11 rounded-full bg-gradient-to-br from-green-600 to-emerald-600
                              text-white hover:shadow-lg hover:scale-110 
                              transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>

                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold
                                         rounded-full h-5 w-5 flex items-center justify-center 
                                         border-2 border-white shadow-lg animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif

                        {{-- Tooltip --}}
                        <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2
                                     bg-gray-900 text-white text-xs py-1 px-3 rounded-lg
                                     opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                     whitespace-nowrap pointer-events-none">
                            Cart
                        </span>
                    </a>
                @endif

                {{-- USER DROPDOWN --}}
                @if($isAuthed)
                    <div class="hidden lg:block relative" id="userDropdown">

                        <div class="w-11 h-11 flex items-center justify-center
                                    rounded-full bg-gradient-to-br from-blue-600 to-indigo-600
                                    text-white cursor-pointer hover:shadow-lg hover:scale-110
                                    transition-all duration-300 group" id="userButton">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>

                        {{-- DROPDOWN --}}
                        <div
                            class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100
                                   transition-all duration-300 py-2 z-50 hidden
                                   transform origin-top-right scale-95 opacity-0"
                            id="userMenu"
                            style="transition: opacity 0.3s, transform 0.3s;"
                        >

                            <div class="px-4 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 
                                                flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-600 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                <a href="{{ route('profile.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 
                                          hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                          transition-all duration-200 group">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                    <span class="font-medium">My Profile</span>
                                </a>

                                <a href="{{ route('orders.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 
                                          hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                          transition-all duration-200 group">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span class="font-medium">My Orders</span>
                                </a>

                                <a href="{{ route('wishlist.index') }}"
                                   class="flex items-center justify-between px-4 py-3 text-sm text-gray-700 
                                          hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                          transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        <span class="font-medium">Wishlist</span>
                                    </div>
                                    @if($wishlistCount > 0)
                                        <span class="bg-gradient-to-r from-pink-500 to-rose-600 text-white text-xs font-bold
                                                     rounded-full h-6 w-6 flex items-center justify-center shadow-md">
                                            {{ $wishlistCount }}
                                        </span>
                                    @endif
                                </a>

                                @if(isset($user->role) && $user->role === 'admin' && Route::has('admin.dashboard'))
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 
                                              hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                              transition-all duration-200 group">
                                        <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="font-medium">Admin Dashboard</span>
                                    </a>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 
                                               hover:bg-red-50 transition-all duration-200 group"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span class="font-medium">Sign Out</span>
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:block group">
                        <button class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600
                                       text-white flex items-center justify-center
                                       hover:shadow-lg hover:scale-110 transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </button>
                    </a>
                @endif

                {{-- MOBILE MENU BUTTON --}}
                <button
                    class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center
                           bg-gradient-to-br from-green-600 to-emerald-600 text-white
                           hover:shadow-lg hover:scale-110 transition-all duration-300 z-50"
                    id="mobileMenuButton"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

            </div>

        </div>
    </div>
</header>

<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

#categoriesMenu:not(.hidden),
#userMenu:not(.hidden) {
    animation: slideDown 0.3s ease-out forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Dropdown Functionality
    const userDropdown = document.getElementById('userDropdown');
    const userButton = document.getElementById('userButton');
    const userMenu = document.getElementById('userMenu');

    if (userDropdown && userButton && userMenu) {
        let userTimeout;

        // Mouse enter - show dropdown
        userDropdown.addEventListener('mouseenter', function() {
            clearTimeout(userTimeout);
            userMenu.classList.remove('hidden');
            setTimeout(() => {
                userMenu.style.opacity = '1';
                userMenu.style.transform = 'scale(1)';
            }, 10);
        });

        // Mouse leave - hide dropdown with delay
        userDropdown.addEventListener('mouseleave', function() {
            userTimeout = setTimeout(function() {
                userMenu.style.opacity = '0';
                userMenu.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    userMenu.classList.add('hidden');
                }, 300);
            }, 200);
        });

        // Click toggle for touch devices
        userButton.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = userMenu.classList.contains('hidden');
            
            if (isHidden) {
                userMenu.classList.remove('hidden');
                setTimeout(() => {
                    userMenu.style.opacity = '1';
                    userMenu.style.transform = 'scale(1)';
                }, 10);
            } else {
                userMenu.style.opacity = '0';
                userMenu.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    userMenu.classList.add('hidden');
                }, 300);
            }
        });
    }

    // Categories Dropdown Functionality
    const categoriesDropdown = document.getElementById('categoriesDropdown');
    const categoriesMenu = document.getElementById('categoriesMenu');

    if (categoriesDropdown && categoriesMenu) {
        let catTimeout;

        // Mouse enter - show dropdown
        categoriesDropdown.addEventListener('mouseenter', function() {
            clearTimeout(catTimeout);
            categoriesMenu.classList.remove('hidden');
            setTimeout(() => {
                categoriesMenu.style.opacity = '1';
                categoriesMenu.style.transform = 'scale(1)';
            }, 10);
        });

        // Mouse leave - hide dropdown with delay
        categoriesDropdown.addEventListener('mouseleave', function() {
            catTimeout = setTimeout(function() {
                categoriesMenu.style.opacity = '0';
                categoriesMenu.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    categoriesMenu.classList.add('hidden');
                }, 300);
            }, 200);
        });
    }

    // Close all dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (userMenu && userDropdown && !userDropdown.contains(e.target)) {
            userMenu.style.opacity = '0';
            userMenu.style.transform = 'scale(0.95)';
            setTimeout(() => {
                userMenu.classList.add('hidden');
            }, 300);
        }
        if (categoriesMenu && categoriesDropdown && !categoriesDropdown.contains(e.target)) {
            categoriesMenu.style.opacity = '0';
            categoriesMenu.style.transform = 'scale(0.95)';
            setTimeout(() => {
                categoriesMenu.classList.add('hidden');
            }, 300);
        }
    });
});
</script> -->
<header class="fixed top-0 left-0 right-0 w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-lg" style="height: 80px;">
    <div class="container mx-auto px-4 lg:px-8 h-full">
        <div class="flex items-center justify-between h-full">

            {{-- LOGO --}}
            <a href="{{ route('shop.home') }}" class="flex items-center z-50 transform hover:scale-105 transition-transform duration-200">
                <div class="relative h-10 w-32 sm:h-12 sm:w-36 lg:h-16 lg:w-48">
                    <img src="/logo.png" alt="Logo" class="h-full w-full object-contain drop-shadow-md">
                </div>
            </a>

            @php
                $user      = auth()->user();
                $isAuthed  = (bool) $user;
                $current   = url()->current();

                $navLinks = [
                    ['name' => 'Home',     'route' => 'shop.home',      'href' => '/'],
                    ['name' => 'Products', 'route' => 'products.index', 'href' => '/products'],
                    ['name' => 'About',    'route' => null,             'href' => '/about'],
                    ['name' => 'Blog',     'route' => 'blog.index',     'href' => '/blog'],
                    ['name' => 'Contact',  'route' => 'contact.index',  'href' => '/contact'],
                ];

                $categoriesList = $categories ?? [];
                $cartCount      = $cartCount ?? 0;
                $wishlistCount  = $wishlistCount ?? 0;
            @endphp

            {{-- DESKTOP NAV --}}
            <nav class="hidden lg:flex items-center space-x-8">

                {{-- MAIN LINKS --}}
                @foreach($navLinks as $link)
                    @php
                        $href = (!empty($link['route']) && Route::has($link['route']))
                                    ? route($link['route'])
                                    : url($link['href']);

                        $active = ($current === $href);
                    @endphp

                    <a href="{{ $href }}"
                       class="text-base font-semibold transition-all duration-300 relative pb-1 group
                              {{ $active ? 'text-green-600' : 'text-gray-700 hover:text-green-600' }}">
                        {{ $link['name'] }}

                        <span class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-600 
                                     transition-all duration-300 {{ $active ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endforeach

                {{-- CATEGORIES DROPDOWN --}}
                @if(count($categoriesList))
                    <div class="relative" id="categoriesDropdown">
                        <button class="text-base font-semibold text-gray-700 hover:text-green-600 pb-1 
                                       transition-colors duration-300 flex items-center gap-1 group">
                            Categories
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            class="absolute left-0 mt-2 w-56 bg-white shadow-2xl rounded-xl 
                                   transition-all duration-300 border border-gray-100 z-50 hidden
                                   transform origin-top scale-95 opacity-0"
                            id="categoriesMenu"
                            style="transition: opacity 0.3s, transform 0.3s;"
                        >
                            <div class="py-2">
                                @foreach($categoriesList as $cat)
                                    @php
                                        $slug = $cat->slug ?? $cat['slug'];
                                        $categoryHref = Route::has('category.show')
                                            ? route('category.show', $slug)
                                            : '#';
                                    @endphp

                                    <a href="{{ $categoryHref }}"
                                       class="block px-4 py-3 text-sm text-gray-700 
                                              hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 
                                              hover:text-green-600 transition-all duration-200
                                              border-l-4 border-transparent hover:border-green-600">
                                        {{ $cat->name ?? $cat['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </nav>

            {{-- RIGHT SIDE --}}
            <div class="flex items-center space-x-3">

                {{-- SEARCH --}}
                <form action="{{ route('products.index') }}" method="GET"
                      class="hidden lg:flex items-center bg-gray-50 rounded-full overflow-hidden 
                             border-2 border-gray-200 focus-within:border-green-500 
                             transition-all duration-300 hover:shadow-md">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search products..."
                           class="px-4 py-2.5 w-56 bg-transparent focus:outline-none text-sm">
                    <button type="submit" 
                            class="px-4 h-full bg-gradient-to-r from-green-600 to-emerald-600 
                                   text-white hover:from-green-700 hover:to-emerald-700 
                                   transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>

                {{-- WISHLIST --}}
                @if($isAuthed)
                    <a href="{{ route('wishlist.index') }}"
                       class="relative group hidden lg:flex items-center justify-center 
                              w-11 h-11 rounded-full bg-gradient-to-br from-pink-500 to-rose-600
                              text-white hover:shadow-lg hover:scale-110 
                              transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>

                        @if($wishlistCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold
                                         rounded-full h-5 w-5 flex items-center justify-center 
                                         border-2 border-white shadow-lg animate-pulse">
                                {{ $wishlistCount }}
                            </span>
                        @endif

                        {{-- Tooltip --}}
                        <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2
                                     bg-gray-900 text-white text-xs py-1 px-3 rounded-lg
                                     opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                     whitespace-nowrap pointer-events-none">
                            Wishlist
                        </span>
                    </a>
                @endif

                {{-- CART --}}
                @if($isAuthed)
                    <button onclick="openCartSidebar()"
                       class="relative group hidden lg:flex items-center justify-center 
                              w-11 h-11 rounded-full bg-gradient-to-br from-green-600 to-emerald-600
                              text-white hover:shadow-lg hover:scale-110 
                              transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>

                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold
                                         rounded-full h-5 w-5 flex items-center justify-center 
                                         border-2 border-white shadow-lg animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif

                        {{-- Tooltip --}}
                        <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2
                                     bg-gray-900 text-white text-xs py-1 px-3 rounded-lg
                                     opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                     whitespace-nowrap pointer-events-none">
                            Cart
                        </span>
                    </button>
                @endif

                {{-- USER DROPDOWN --}}
                @if($isAuthed)
                    <div class="hidden lg:block relative" id="userDropdown">

                        <div class="w-11 h-11 flex items-center justify-center
                                    rounded-full bg-gradient-to-br from-blue-600 to-indigo-600
                                    text-white cursor-pointer hover:shadow-lg hover:scale-110
                                    transition-all duration-300 group" id="userButton">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>

                        {{-- DROPDOWN --}}
                        <div
                            class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100
                                   transition-all duration-300 py-2 z-50 hidden
                                   transform origin-top-right scale-95 opacity-0"
                            id="userMenu"
                            style="transition: opacity 0.3s, transform 0.3s;"
                        >

                            <div class="px-4 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 
                                                flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-600 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                <a href="{{ route('profile.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 
                                          hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                          transition-all duration-200 group">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                    <span class="font-medium">My Profile</span>
                                </a>

                                <a href="{{ route('orders.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 
                                          hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                          transition-all duration-200 group">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span class="font-medium">My Orders</span>
                                </a>

                                <a href="{{ route('wishlist.index') }}"
                                   class="flex items-center justify-between px-4 py-3 text-sm text-gray-700 
                                          hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                          transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        <span class="font-medium">Wishlist</span>
                                    </div>
                                    @if($wishlistCount > 0)
                                        <span class="bg-gradient-to-r from-pink-500 to-rose-600 text-white text-xs font-bold
                                                     rounded-full h-6 w-6 flex items-center justify-center shadow-md">
                                            {{ $wishlistCount }}
                                        </span>
                                    @endif
                                </a>

                                @if(isset($user->role) && $user->role === 'admin' && Route::has('admin.dashboard'))
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 
                                              hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50
                                              transition-all duration-200 group">
                                        <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="font-medium">Admin Dashboard</span>
                                    </a>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 
                                               hover:bg-red-50 transition-all duration-200 group"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span class="font-medium">Sign Out</span>
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:block group">
                        <button class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600
                                       text-white flex items-center justify-center
                                       hover:shadow-lg hover:scale-110 transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </button>
                    </a>
                @endif

                {{-- MOBILE MENU BUTTON --}}
                <button
                    class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center
                           bg-gradient-to-br from-green-600 to-emerald-600 text-white
                           hover:shadow-lg hover:scale-110 transition-all duration-300 z-50"
                    id="mobileMenuButton"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

            </div>

        </div>
    </div>
</header>

<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

#categoriesMenu:not(.hidden),
#userMenu:not(.hidden) {
    animation: slideDown 0.3s ease-out forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Dropdown Functionality
    const userDropdown = document.getElementById('userDropdown');
    const userButton = document.getElementById('userButton');
    const userMenu = document.getElementById('userMenu');

    if (userDropdown && userButton && userMenu) {
        let userTimeout;

        // Mouse enter - show dropdown
        userDropdown.addEventListener('mouseenter', function() {
            clearTimeout(userTimeout);
            userMenu.classList.remove('hidden');
            setTimeout(() => {
                userMenu.style.opacity = '1';
                userMenu.style.transform = 'scale(1)';
            }, 10);
        });

        // Mouse leave - hide dropdown with delay
        userDropdown.addEventListener('mouseleave', function() {
            userTimeout = setTimeout(function() {
                userMenu.style.opacity = '0';
                userMenu.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    userMenu.classList.add('hidden');
                }, 300);
            }, 200);
        });

        // Click toggle for touch devices
        userButton.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = userMenu.classList.contains('hidden');
            
            if (isHidden) {
                userMenu.classList.remove('hidden');
                setTimeout(() => {
                    userMenu.style.opacity = '1';
                    userMenu.style.transform = 'scale(1)';
                }, 10);
            } else {
                userMenu.style.opacity = '0';
                userMenu.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    userMenu.classList.add('hidden');
                }, 300);
            }
        });
    }

    // Categories Dropdown Functionality
    const categoriesDropdown = document.getElementById('categoriesDropdown');
    const categoriesMenu = document.getElementById('categoriesMenu');

    if (categoriesDropdown && categoriesMenu) {
        let catTimeout;

        // Mouse enter - show dropdown
        categoriesDropdown.addEventListener('mouseenter', function() {
            clearTimeout(catTimeout);
            categoriesMenu.classList.remove('hidden');
            setTimeout(() => {
                categoriesMenu.style.opacity = '1';
                categoriesMenu.style.transform = 'scale(1)';
            }, 10);
        });

        // Mouse leave - hide dropdown with delay
        categoriesDropdown.addEventListener('mouseleave', function() {
            catTimeout = setTimeout(function() {
                categoriesMenu.style.opacity = '0';
                categoriesMenu.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    categoriesMenu.classList.add('hidden');
                }, 300);
            }, 200);
        });
    }

    // Close all dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (userMenu && userDropdown && !userDropdown.contains(e.target)) {
            userMenu.style.opacity = '0';
            userMenu.style.transform = 'scale(0.95)';
            setTimeout(() => {
                userMenu.classList.add('hidden');
            }, 300);
        }
        if (categoriesMenu && categoriesDropdown && !categoriesDropdown.contains(e.target)) {
            categoriesMenu.style.opacity = '0';
            categoriesMenu.style.transform = 'scale(0.95)';
            setTimeout(() => {
                categoriesMenu.classList.add('hidden');
            }, 300);
        }
    });
});
</script>
<!-- @php
    $user      = auth()->user();
    $isAuthed  = (bool) $user;
    $current   = url()->current();

    $navLinks = [
        ['name' => 'Home',     'route' => 'shop.home',      'href' => '/'],
        ['name' => 'Products', 'route' => 'products.index', 'href' => '/products'],
        ['name' => 'About',    'route' => null,             'href' => '/about'],
        ['name' => 'Blog',     'route' => null,             'href' => '/blog'],
        ['name' => 'Contact',  'route' => null,             'href' => '/contact'],
    ];

    $categoriesList = $categories ?? [];
    $cartCount      = $cartCount ?? 0;
    $wishlistCount  = $wishlistCount ?? 0;
@endphp

{{-- HEADER --}}
<header class="fixed w-full z-50 transition-all duration-300 bg-white shadow-lg border-b border-gray-100">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            {{-- LOGO --}}
            <a href="{{ route('shop.home') }}" class="flex items-center z-50">
                <div class="relative h-10 w-32 sm:h-12 sm:w-36 lg:h-16 lg:w-48">
                    <img src="/logo.png" alt="Logo" class="h-full w-full object-contain">
                </div>
            </a>

            {{-- DESKTOP NAV --}}
            <nav class="hidden lg:flex items-center space-x-8">
                @foreach($navLinks as $link)
                    @php
                        $href = (!empty($link['route']) && Route::has($link['route']))
                                    ? route($link['route'])
                                    : url($link['href']);
                        $active = ($current === $href);
                    @endphp

                    <a href="{{ $href }}"
                       class="text-base font-medium transition-colors relative pb-1
                              {{ $active ? 'text-green-600' : 'text-gray-700 hover:text-green-600' }}">
                        {{ $link['name'] }}
                        @if($active)
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-green-600"></div>
                        @endif
                    </a>
                @endforeach

                {{-- CATEGORIES DROPDOWN --}}
                @if(count($categoriesList))
                    <div class="relative group">
                        <button class="text-base font-medium text-gray-700 hover:text-green-600 pb-1">
                            Categories
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-100 z-50">
                            @foreach($categoriesList as $cat)
                                @php
                                    $slug = $cat->slug ?? $cat['slug'];
                                    $categoryHref = Route::has('category.show') ? route('category.show', $slug) : '#';
                                @endphp
                                <a href="{{ $categoryHref }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">
                                    {{ $cat->name ?? $cat['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </nav>

            {{-- RIGHT SIDE --}}
            <div class="flex items-center space-x-4">

                {{-- DESKTOP SEARCH --}}
                <form action="{{ route('products.index') }}" method="GET"
                      class="hidden lg:flex items-center border-2 border-green-600 rounded-full overflow-hidden">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search products..."
                           class="px-4 py-2 w-64 focus:outline-none">
                    <button type="submit" class="w-12 h-12 bg-green-600 text-white flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>

                {{-- CART --}}
                @if($isAuthed)
                    <button onclick="openCartSidebar()"
                            class="relative w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-green-600 text-white hover:scale-110 transition-transform duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </button>
                @endif

                {{-- USER ICON / DROPDOWN --}}
                @if($isAuthed)
                    <div class="hidden lg:block relative group">
                        <button class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-green-600 text-white hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </button>

                        {{-- USER DROPDOWN --}}
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>

                            <a href="{{ route('profile.index') }}" class="block px-4 py-2.5 text-sm hover:bg-green-50">
                                My Profile
                            </a>

                            <a href="{{ route('orders.index') }}" class="block px-4 py-2.5 text-sm hover:bg-green-50">
                                My Orders
                            </a>

                            <a href="{{ route('wishlist.index') }}" class="px-4 py-2.5 text-sm hover:bg-green-50 flex justify-between">
                                Wishlist
                                @if($wishlistCount > 0)
                                    <span class="bg-green-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $wishlistCount }}
                                    </span>
                                @endif
                            </a>

                            @if($user->role === 'admin' && Route::has('admin.dashboard'))
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm hover:bg-green-50">
                                    Admin Dashboard
                                </a>
                            @endif

                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:block">
                        <button class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-green-600 text-white flex items-center justify-center hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </button>
                    </a>
                @endif

                {{-- MOBILE MENU TOGGLE --}}
                <button id="mobileMenuButton"
                        class="lg:hidden border-2 border-green-600 text-green-600 w-10 h-10 rounded-full flex items-center justify-center z-50 relative hover:scale-110 transition-transform duration-200">
                    <svg id="menuIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

{{-- MOBILE MENU OVERLAY --}}
<div id="mobileMenuOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm hidden opacity-0 transition-opacity duration-300"></div>

{{-- MOBILE SLIDING MENU --}}
<div id="mobileMenu" 
     class="fixed top-0 left-0 h-full w-80 bg-gradient-to-br from-white via-green-50/30 to-white shadow-2xl z-50 lg:hidden overflow-y-auto transform -translate-x-full transition-transform duration-300">
    
    {{-- MENU HEADER --}}
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 pt-20">
        @if($isAuthed)
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/50">
                    <svg class="h-7 w-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-lg">{{ $user->name }}</p>
                    <p class="text-green-100 text-sm truncate max-w-[180px]">{{ $user->email }}</p>
                </div>
            </div>
        @else
            <div>
                <h2 class="text-white font-bold text-2xl">Welcome!</h2>
                <p class="text-green-100 text-sm mt-1">Sign in to continue</p>
            </div>
        @endif
    </div>

    {{-- MENU CONTENT --}}
    <div class="py-4">
        {{-- NAVIGATION LINKS --}}
        <div class="px-4 space-y-1">
            @foreach($navLinks as $link)
                @php
                    $href = (!empty($link['route']) && Route::has($link['route']))
                                ? route($link['route'])
                                : url($link['href']);
                    $active = ($current === $href);
                @endphp
                
                <a href="{{ $href }}"
                   class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base font-medium transition-all duration-200
                          {{ $active ? 'bg-gradient-to-r from-green-600 to-green-700 text-white shadow-lg shadow-green-600/30' : 'text-gray-700 hover:bg-green-50 hover:translate-x-1' }}">
                    <span>{{ $link['name'] }}</span>
                    @if($active)
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- CATEGORIES SECTION --}}
        @if(count($categoriesList))
            <div class="mt-6 px-4">
                <div class="mb-3 flex items-center space-x-2">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <p class="text-gray-500 text-xs uppercase font-semibold tracking-wider">Categories</p>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>
                <div class="space-y-1">
                    @foreach($categoriesList as $cat)
                        @php
                            $slug = $cat->slug ?? $cat['slug'];
                            $categoryHref = Route::has('category.show') ? route('category.show', $slug) : '#';
                        @endphp
                        <a href="{{ $categoryHref }}"
                           class="flex items-center justify-between px-4 py-3 rounded-xl text-base text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-green-100/50 transition-all duration-200 hover:translate-x-1 group">
                            <span class="group-hover:text-green-700">{{ $cat->name ?? $cat['name'] }}</span>
                            <svg class="h-4 w-4 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ACCOUNT SECTION --}}
        @if($isAuthed)
            <div class="mt-6 px-4">
                <div class="mb-3 flex items-center space-x-2">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <p class="text-gray-500 text-xs uppercase font-semibold tracking-wider">My Account</p>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.index') }}"
                       class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100/50 transition-all duration-200 hover:translate-x-1 group">
                        <span class="group-hover:text-blue-700">My Profile</span>
                        <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('orders.index') }}"
                       class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100/50 transition-all duration-200 hover:translate-x-1 group">
                        <span class="group-hover:text-purple-700">My Orders</span>
                        <svg class="h-4 w-4 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('wishlist.index') }}"
                       class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base text-gray-700 hover:bg-gradient-to-r hover:from-pink-50 hover:to-pink-100/50 transition-all duration-200 hover:translate-x-1 group">
                        <div class="flex items-center space-x-2">
                            <span class="group-hover:text-pink-700">Wishlist</span>
                            @if($wishlistCount > 0)
                                <span class="bg-gradient-to-r from-pink-500 to-pink-600 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-semibold shadow-lg">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        </div>
                        <svg class="h-4 w-4 text-gray-400 group-hover:text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    @if($user->role === 'admin' && Route::has('admin.dashboard'))
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base text-gray-700 hover:bg-gradient-to-r hover:from-amber-50 hover:to-amber-100/50 transition-all duration-200 hover:translate-x-1 group">
                            <span class="group-hover:text-amber-700">Admin Dashboard</span>
                            <svg class="h-4 w-4 text-gray-400 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>

                {{-- SIGN OUT BUTTON --}}
                <div class="mt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-4 rounded-xl font-semibold text-base transition-all duration-300 shadow-lg hover:shadow-xl shadow-red-500/30 flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="mt-6 px-4">
                <a href="{{ route('login') }}"
                   class="block w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-4 rounded-xl font-semibold text-base transition-all duration-300 shadow-lg hover:shadow-xl shadow-green-600/30 text-center">
                    Sign In
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');

    function openMobileMenu() {
        mobileMenu.classList.remove('-translate-x-full');
        mobileMenuOverlay.classList.remove('hidden');
        setTimeout(() => {
            mobileMenuOverlay.classList.remove('opacity-0');
        }, 10);
        menuIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu.classList.add('-translate-x-full');
        mobileMenuOverlay.classList.add('opacity-0');
        setTimeout(() => {
            mobileMenuOverlay.classList.add('hidden');
        }, 300);
        menuIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        document.body.style.overflow = '';
    }

    mobileMenuButton.addEventListener('click', function() {
        if (mobileMenu.classList.contains('-translate-x-full')) {
            openMobileMenu();
        } else {
            closeMobileMenu();
        }
    });

    mobileMenuOverlay.addEventListener('click', closeMobileMenu);

    // Close mobile menu on link click
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
});
</script> -->