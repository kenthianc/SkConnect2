<!-- Top Navbar Component -->
<nav class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center space-x-4">
        <!-- Page Title -->
        <h2 class="text-gray-900 hidden md:block">@yield('page-title', 'Dashboard')</h2>
    </div>
    
    <div class="flex items-center space-x-4">
        <!-- User Profile Dropdown -->
        <div x-data="{ open: false, showLogoutModal: false }" class="relative">
            <button 
                @click="open = !open"
                class="flex items-center space-x-3 px-3 py-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-600"></i>
            </button>
            
            <!-- User Dropdown Menu -->
            <div 
                x-show="open"
                @click.away="open = false"
                x-cloak
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
            >
                <a href="{{ route('settings.profile') }}" class="flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    <span>Settings</span>
                </a>
                <hr class="my-2 border-gray-200">
                <button 
                    @click="open = false; showLogoutModal = true"
                    class="w-full flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50 transition-colors"
                >
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </button>
            </div>
            
            <!-- Logout Confirmation Modal -->
            <div 
                x-show="showLogoutModal"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
            >
                <div class="absolute inset-0 bg-black/40" @click="showLogoutModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i data-lucide="log-out" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-900">Confirm Logout</h3>
                            <p class="text-sm text-gray-600">Are you sure you want to logout?</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button 
                            @click="showLogoutModal = false"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
