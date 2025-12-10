<!DOCTYPE html>
<html lang="en">
<head>
   
<script src="https://js.stripe.com/v3/"></script>
    <meta charset="UTF-8">
    <title>@yield('title', 'Store')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="stripe-key" content="{{ config('services.stripe.key') }}">

    {{-- CSRF Token - Required for AJAX requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind CDN (simple, no Vite needed) --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
    >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    {{-- Navbar --}}
    @include('shared.navbar')

    <main class="flex-grow" style="padding-top: 80px; min-height: calc(100vh - 80px);">
        @yield('content')
    </main>
 
    {{-- Footer --}}
    @include('shared.footer')
    
    {{-- Cart Sidebar --}}
    @include('shared.cart-sidebar')
    
    
    @stack('scripts')
</body>
</html>
