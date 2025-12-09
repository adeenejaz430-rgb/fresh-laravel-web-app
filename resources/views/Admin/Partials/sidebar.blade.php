{{-- resources/views/admin/partials/sidebar.blade.php --}}
@php
    use Illuminate\Support\Facades\Route as RouteFacade;

    $navItems = [
        ['name' => 'Dashboard', 'route' => 'admin.dashboard',        'icon' => 'home'],
        ['name' => 'Products',  'route' => 'admin.products.index',   'icon' => 'bag'],
        ['name' => 'Categories','route' => 'admin.categories.index', 'icon' => 'chart'],
        // placeholder - no route yet
        ['name' => 'Blogs',     'route' => null,                     'icon' => 'chart'],
        ['name' => 'Customers', 'route' => 'admin.customers.index',  'icon' => 'users'],
        ['name' => 'Orders',    'route' => 'admin.orders.index',     'icon' => 'cart'],
        // if you don't have admin.settings.index route yet, keep this null:
        ['name' => 'Settings',  'route' => null,                     'icon' => 'settings'],
    ];

    $currentRoute = request()->route() ? request()->route()->getName() : null;

    // icon paths instead of a PHP function (so no redeclare problem)
    $iconPaths = [
        'home'     => 'M3 12l9-9 9 9v9a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4H9v4a1 1 0 01-1 1H4a1 1 0 01-1-1z',
        'bag'      => 'M6 7V6a4 4 0 018 0v1h3a1 1 0 011 1v9a2 2 0 01-2 2H4a2 2 0 01-2-2V8a1 1 0 011-1h3zM8 7h8V6a2 2 0 10-4 0v1',
        'chart'    => 'M4 19h4V9H4v10zm6 0h4V5h-4v14zm6 0h4V12h-4v7z',
        'users'    => 'M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14-10a4 4 0 11-8 0 4 4 0 018 0z',
        'cart'     => 'M3 3h2l.4 2M7 13h10l1.2-6H6.2L5.4 3M7 13L5 6m2 7l-2 6m10-6l2 6M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z',
        'settings' => 'M11.05 4.05L12 2l.95 2.05a1 1 0 00.63.57l2.12.64-1.5 1.5a1 1 0 00-.29.88l.35 2.24-2.02-.84a1 1 0 00-.76 0l-2.02.84.35-2.24a1 1 0 00-.29-.88l-1.5-1.5 2.12-.64a1 1 0 00.63-.57z',
    ];
@endphp

<div class="h-full flex flex-col">
    <div class="p-4 border-b">
        <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
    </div>

    <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-2">
            @foreach($navItems as $item)
                @php
                    // Only “enabled” if a route name is set AND exists
                    $hasRoute = $item['route'] && RouteFacade::has($item['route']);
                    $isActive = $hasRoute && $currentRoute === $item['route'];
                    $iconPath = $iconPaths[$item['icon']] ?? '';
                @endphp

                @if (! $hasRoute)
                    <li>
                        <span class="flex items-center p-2 rounded-md text-gray-400 cursor-not-allowed opacity-60">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="{{ $iconPath }}" />
                            </svg>
                            {{ $item['name'] }} (soon)
                        </span>
                    </li>
                @else
                    <li>
                        <a
                            href="{{ route($item['route']) }}"
                            class="flex items-center p-2 rounded-md
                                   {{ $isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="{{ $iconPath }}" />
                            </svg>
                            {{ $item['name'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    <div class="p-4 border-t">
        <a href="{{ route('shop.home') }}" class="flex items-center p-2 text-gray-600 hover:bg-gray-100 rounded-md">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1a9 9 0 019-9h1"/>
            </svg>
            Back to Store
        </a>
    </div>
</div>
