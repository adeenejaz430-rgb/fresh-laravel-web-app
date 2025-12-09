<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Auth')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ Use Tailwind CDN instead of Vite --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- (Optional) Google Material Icons if you need them on auth pages --}}
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
    />
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-b from-blue-50 to-blue-100">

    <main class="flex-grow flex items-center justify-center">
        @yield('content')
    </main>

</body>
</html>
