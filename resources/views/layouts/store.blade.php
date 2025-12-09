<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Store')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- CSRF Token - Required for AJAX requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind CDN (simple, no Vite needed) --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
    >
    
    <style>
        /* Smooth transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    {{-- Navbar --}}
    @include('shared.navbar')

    <main class="flex-grow pt-16">
        @yield('content')
    </main>
 
    {{-- Footer --}}
    @include('shared.footer')
    
    {{-- Cart Sidebar --}}
    @include('shared.cart-sidebar')
    
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>