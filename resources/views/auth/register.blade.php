@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Create Account</h1>
            <p class="text-gray-600 mt-2">Join our fountain community</p>
        </div>

        @if (session('error'))
            <div class="bg-red-50 text-red-500 p-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 text-green-600 p-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    required
                />
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    required
                />
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    required
                    minlength="6"
                />
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                    Password must be at least 6 characters
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    required
                />
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 flex justify-center"
            >
                Create Account
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                Sign in
            </a>
        </p>
    </div>
</div>
@endsection
