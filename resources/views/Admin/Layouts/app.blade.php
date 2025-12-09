{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

   {{-- Tailwind CSS via CDN (quick setup) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="min-h-screen bg-gray-100">

{{-- Hide frontend navbar on admin pages --}}
<style>
    header.fixed { display: none !important; }
</style>

<div x-data="{ mobileOpen: false }" class="min-h-screen" x-cloak>
    {{-- Mobile menu button --}}
    <div class="lg:hidden fixed top-4 left-4 z-30">
        <button
            @click="mobileOpen = !mobileOpen"
            class="p-2 rounded-md bg-white shadow-md text-gray-600 hover:text-gray-900 focus:outline-none"
            aria-label="Toggle mobile menu"
        >
            <template x-if="!mobileOpen">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </template>
            <template x-if="mobileOpen">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </template>
        </button>
    </div>

    {{-- Mobile sidebar overlay --}}
    <div
        class="lg:hidden fixed inset-0 z-20 bg-gray-900 bg-opacity-50"
        x-show="mobileOpen"
        x-transition.opacity
        @click="mobileOpen = false"
    ></div>

    {{-- Mobile sidebar --}}
    <div
        class="lg:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-30"
        x-show="mobileOpen"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        @click.stop
    >
        @include('admin.partials.sidebar')
    </div>

    {{-- Desktop sidebar --}}
    <div class="hidden lg:block fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-10">
        @include('admin.partials.sidebar')
    </div>

    {{-- Main content --}}
    <div class="lg:ml-64">
        <main class="p-4 md:p-8">
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

{{-- Alpine.js (for sidebar toggles) – only if you don't already use it --}}
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
