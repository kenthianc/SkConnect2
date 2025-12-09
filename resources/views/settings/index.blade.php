@extends('layouts.dashboard')

@section('page-title', 'Settings')

@section('page-content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-gray-900 mb-2">Settings</h1>
        <p class="text-gray-600">Manage your account and system preferences</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <nav class="space-y-1">
                    <a href="#profile" class="flex items-center space-x-3 px-4 py-3 text-blue-600 bg-blue-50 rounded-lg">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span>Profile</span>
                    </a>  
                    <a href="#security" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <i data-lucide="shield" class="w-5 h-5"></i>
                        <span>Security</span>
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Settings Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Settings -->
            <div id="profile" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h3 class="text-gray-900 mb-1">Profile Information</h3>
                    <p class="text-sm text-gray-600">Update your account profile information</p>
                </div>
                
                <form action="{{ route('settings.update-profile') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Profile Picture 
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-semibold">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Change Photo
                            </button>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF. Max 2MB</p>
                        </div>
                    </div> -->
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ Auth::user()->name ?? '' }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ Auth::user()->email ?? '' }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Security Settings -->
            <div id="security" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h3 class="text-gray-900 mb-1">Change Password</h3>
                    <p class="text-sm text-gray-600">Update your password to keep your account secure</p>
                </div>
                
                <form action="{{ route('settings.update-password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Current Password</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">New Password</label>
                        <input 
                            type="password" 
                            name="new_password" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Confirm New Password</label>
                        <input 
                            type="password" 
                            name="new_password_confirmation" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
