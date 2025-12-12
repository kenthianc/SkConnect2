@extends('layouts.app')

@section('title', 'Register - SK Portal')

@section('content')
<div class="min-h-screen flex">
<!-- Left Side - SK Logo Slideshow -->
<div class="hidden lg:flex lg:w-1/2 items-center justify-center p-0 overflow-hidden">
    <div class="relative w-full h-full overflow-hidden">
       <!-- Fade Slideshow (replace images later as needed) -->
       <div class="fade-slide slide-1 bg-cover bg-center"
           style="background-image: url('{{ asset('images/image1.png') }}');"></div>
       <div class="fade-slide slide-2 bg-cover bg-center"
           style="background-image: url('{{ asset('images/image2.png') }}');"></div>
       <div class="fade-slide slide-3 bg-cover bg-center"
           style="background-image: url('{{ asset('images/image3.png') }}');"></div>
    </div>
</div>



<style>
    .fade-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        animation: fadeShow 12s infinite ease-in-out;
        will-change: opacity;
    }

    .fade-slide.slide-1 { animation-delay: 0s; }
    .fade-slide.slide-2 { animation-delay: 4s; }
    .fade-slide.slide-3 { animation-delay: 8s; }

    /* 12s total, 3 slides x 4s each
       Each slide: fade in -> hold -> fade out
    */
    @keyframes fadeShow {
        0%   { opacity: 0; }
        10%  { opacity: 1; }
        30%  { opacity: 1; }
        40%  { opacity: 0; }
        100% { opacity: 0; }
    }


</style>
    
    <!-- Right Side - Register Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="text-center mb-8 lg:hidden">
                <img src="{{ asset('images/sk-logo.png') }}" alt="SK Logo" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-blue-600">SK Portal</h1>
                <p class="text-gray-600">Admin Dashboard</p>
            </div>
            
            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <div class="mb-8">
                    <h1 class="text-blue-600 mb-2 font-bold text-2xl">Creat Account</h1>
                    <p class="text-gray-600">Register as SK Admin to get started</p>
                </div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Register Form -->
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="Juan Dela Cruz"
                            >
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
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
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                placeholder="admin@skportal.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
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
                                class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                placeholder="Min. 8 characters"
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
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                :type="show ? 'text' : 'password'"
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Re-enter password"
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
                    </div>
                    
                    <!-- Terms Checkbox -->
                    <div>
                        <label class="flex items-start">
                            <input 
                                type="checkbox" 
                                name="terms" 
                                required
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1"
                            >
                            <span class="ml-2 text-sm text-gray-600">
                                I agree to the <a href="{{ route('legal.terms') }}" class="text-blue-600 hover:text-blue-700">Terms and Conditions</a> and <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
                            </span>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Create Account
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
