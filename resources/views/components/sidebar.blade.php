<!-- Sidebar Component -->
<aside 
    class="w-64 bg-white border-r border-gray-200 flex flex-col"
>
    <!-- Logo Section -->
    <div class="h-16 flex items-center px-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/sk-logo.png') }}" alt="SK Logo" class="w-10 h-10">
            <div x-show="!sidebarCollapsed" x-transition>
                <h1 class="text-xl font-bold text-blue-600">SK Connect</h1>
                <p class="text-xs text-gray-500">Admin Dashboard</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4">
        <div class="px-3 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span x-show="!sidebarCollapsed" x-transition>Dashboard</span>
            </a>
            
            <!-- Members -->
            <a href="{{ route('members.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('members.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span x-show="!sidebarCollapsed" x-transition>Members</span>
            </a>
            
            <!-- Events -->
            <a href="{{ route('events.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('events.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span x-show="!sidebarCollapsed" x-transition>Events</span>
            </a>
            
            <!-- Attendance
            <a href="{{ route('attendance.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('attendance.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                <span x-show="!sidebarCollapsed" x-transition>Attendance</span>
            </a>-->
            
            <!-- News -->
            <a href="{{ route('news.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('news.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
                <span x-show="!sidebarCollapsed" x-transition>News</span>
            </a>
            
            <!-- Settings -->
            <a href="{{ route('settings.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span x-show="!sidebarCollapsed" x-transition>Settings</span>
            </a>
        </div>
    </nav>
</aside>
