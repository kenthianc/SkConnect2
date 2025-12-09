@extends('layouts.app')

@section('title', 'Login - SK Portal')

@section('content')
<div class="min-h-screen flex">
<!-- Left Side - SK Logo Slideshow -->
<div class="hidden lg:flex lg:w-1/2 items-center justify-center p-0 overflow-hidden">
    <div class="relative w-full h-full overflow-hidden">
        <div class="slides flex w-[300%] h-full animate-slide">
            <!-- Slide 1 -->
            <div class="w-1/3 h-full bg-cover bg-center"
                 style="background-image: url('{{ asset('images/LoginPage.png') }}');">
            </div>

            <!-- Slide 2 -->
            <div class="w-1/3 h-full bg-cover bg-center"
                 style="background-image: url('{{ asset('images/LoginPage.png') }}');">
            </div>

            <!-- Slide 3 -->
            <div class="w-1/3 h-full bg-cover bg-center"
                 style="background-image: url('{{ asset('images/LoginPage.png') }}');">
            </div>
        </div>
    </div>
</div>



<style>
    .slides {
        animation: slideShow 12s infinite ease-in-out;
        will-change: transform;
    }

    /* Total 12s:
       - 3s pause on each slide
       - ~1s sliding between slides
    */
    @keyframes slideShow {
        /* Slide 1 visible */
        0% {
            transform: translateX(0);
        }
        20% {
            transform: translateX(0);
        }

        /* Slide 1 -> Slide 2 */
        25% {
            transform: translateX(-100%);
        }

        /* Slide 2 visible */
        45% {
            transform: translateX(-100%);
        }

        /* Slide 2 -> Slide 3 */
        50% {
            transform: translateX(-200%);
        }

        /* Slide 3 visible */
        70% {
            transform: translateX(-200%);
        }

        /* Slide 3 -> back to Slide 1 */
        75% {
            transform: translateX(0);
        }

        /* Slide 1 visible again */
        100% {
            transform: translateX(0);
        }
    }


</style>



    
    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="text-center mb-8 lg:hidden">
                <img src="{{ asset('images/sk-logo.png') }}" alt="SK Logo" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-blue-600">SK Portal</h1>
                <p class="text-gray-600">Admin Dashboard</p>
            </div>
            
            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <div class="mb-8">
                    <h1 class="text-blue-600 mb-2 font-bold text-2xl">Login</h1>
                    <p class="text-gray-600">Sign in to your account to continue</p>
                </div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-red-800">There were some errors with your submission:</p>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                placeholder="admin@skportal.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                :type="show ? 'text' : 'password'"
                                id="password" 
                                name="password" 
                                required
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                placeholder="Enter your password"
                            >
                            <button 
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <i x-show="!show" data-lucide="eye" class="w-5 h-5 text-gray-400"></i>
                                <i x-show="show" data-lucide="eye-off" class="w-5 h-5 text-gray-400"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700">
                            Forgot password?
                        </a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Sign In
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Register here
                        </a>
                    </p>
                </div>
                
                <!-- Demo Credentials -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm font-medium text-blue-900 mb-2">Demo Credentials:</p>
                    <p class="text-sm text-blue-700">Email: admin@skportal.com</p>
                    <p class="text-sm text-blue-700">Password: admin123</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
