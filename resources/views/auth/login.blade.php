@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-600 mt-2">Sign in to your account</p>
        </div>

        @if (session('error'))
            <div class="bg-red-50 text-red-500 p-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- Step 1: email + password | Step 2: verification code --}}
        @php
            $verificationStep = session('verification_step', false);
            $email = old('email', session('email'));
        @endphp

        @if (! $verificationStep)
            {{-- STEP 1: send verification code --}}
            <form method="POST" action="{{ route('login.sendCode') }}" class="space-y-6">
                @csrf

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
                    />
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            {{ old('remember') ? 'checked' : '' }}
                        />
                        <label for="remember" className="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ url('/forgot-password') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 flex justify-center"
                >
                    Send Verification Code
                </button>
            </form>
        @else
            {{-- STEP 2: verify code & login --}}
            <form method="POST" action="{{ route('login.verifyCode') }}" class="space-y-6">
                @csrf

                {{-- keep email hidden so we know which user --}}
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="verificationCode" class="block text-sm font-medium text-gray-700 mb-1">
                        Verification Code
                    </label>
                    <p class="text-sm text-gray-500 mb-2">
                        Enter the 6-digit code sent to your email
                    </p>
                    <input
                        id="verificationCode"
                        name="code"
                        type="text"
                        value="{{ old('code') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required
                        maxlength="6"
                        placeholder="123456"
                    />
                    @error('code')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <div class="mt-2 text-right">
                        {{-- resend code: submit to sendCode again with email --}}
                        <button
                            type="submit"
                            formaction="{{ route('login.sendCode') }}"
                            class="text-sm text-blue-600 hover:text-blue-800"
                        >
                            Resend Code
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button
                        type="button"
                        onclick="window.location='{{ route('login') }}'"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        Back to Login
                    </button>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 flex justify-center"
                >
                    Verify &amp; Sign In
                </button>
            </form>
        @endif

        {{-- Divider --}}
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="mt-6">
                <a
                    href="{{ url('/auth/google/redirect') }}" {{-- Socialite route --}}
                    class="w-full flex justify-center items-center gap-3 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50 transition duration-200"
                >
                    {{-- Google icon same as your SVG --}}
                    <svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M19.9895 10.1871C19.9895 9.36767 19.9214 8.76973 19.7742 8.14966H10.1992V11.848H15.8195C15.7062 12.7671 15.0943 14.1512 13.7346 15.0813L13.7155 15.2051L16.7429 17.4969L16.9527 17.5174C18.879 15.7789 19.9895 13.221 19.9895 10.1871Z" fill="#4285F4" />
                        <path d="M10.1993 19.9313C12.9527 19.9313 15.2643 19.0454 16.9527 17.5174L13.7346 15.0813C12.8734 15.6682 11.7176 16.0779 10.1993 16.0779C7.50243 16.0779 5.21352 14.3395 4.39759 11.9366L4.27799 11.9466L1.13003 14.3273L1.08887 14.4391C2.76588 17.6945 6.21061 19.9313 10.1993 19.9313Z" fill="#34A853" />
                        <path d="M4.39748 11.9366C4.18219 11.3166 4.05759 10.6521 4.05759 9.96565C4.05759 9.27909 4.18219 8.61473 4.38615 7.99466L4.38045 7.8626L1.19304 5.44366L1.08875 5.49214C0.397576 6.84305 0.000976562 8.36008 0.000976562 9.96565C0.000976562 11.5712 0.397576 13.0882 1.08875 14.4391L4.39748 11.9366Z" fill="#FBBC05" />
                        <path d="M10.1993 3.85336C12.1142 3.85336 13.406 4.66168 14.1425 5.33717L17.0207 2.59107C15.253 0.985496 12.9527 0 10.1993 0C6.2106 0 2.76588 2.23672 1.08887 5.49214L4.38626 7.99466C5.21352 5.59183 7.50242 3.85336 10.1993 3.85336Z" fill="#EB4335" />
                    </svg>
                    Sign in with Google
                </a>
            </div>
        </div>

        <p class="mt-8 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                Sign up
            </a>
        </p>
    </div>
</div>
@endsection
