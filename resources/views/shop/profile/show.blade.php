@extends('layouts.store')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 lg:px-8 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: user info / sidebar --}}
        <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100">
            <div class="flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-green-100 flex items-center justify-center mb-4 text-3xl font-bold text-green-700">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-1">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm mb-4">{{ $user->email }}</p>

                @if(session('status'))
                    <div class="w-full bg-green-50 text-green-700 text-sm px-3 py-2 rounded-lg mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="w-full mt-2">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-full text-sm font-semibold transition-colors"
                    >
                        Sign Out
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: profile form + recent orders --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Profile form --}}
            <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Account Details</h3>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                            required
                        >
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                            required
                        >
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                New Password <span class="text-gray-400 text-xs">(optional)</span>
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-full text-sm font-semibold transition-colors"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Recent orders (optional) --}}
            <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Orders</h3>

                @if($orders->isEmpty())
                    <p class="text-sm text-gray-500">You have no recent orders yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($orders as $order)
                            <div class="flex justify-between items-center border rounded-xl px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        Order #{{ $order->id }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->created_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-800">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </p>
                                    <p class="text-xs text-green-600 uppercase font-semibold">
                                        {{ strtoupper($order->status) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
