<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SK Connect - Admin Dashboard')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Typography */
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        h1 { font-size: 1.875rem; font-weight: 700; line-height: 2.25rem; }
        h2 { font-size: 1.5rem; font-weight: 600; line-height: 2rem; }
        h3 { font-size: 1.25rem; font-weight: 600; line-height: 1.75rem; }
        h4 { font-size: 1.125rem; font-weight: 600; line-height: 1.5rem; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased">
    @yield('content')
    
    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            toast.textContent = message;
            
            document.getElementById('toast-container').appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // CSRF Token Setup for AJAX
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    
    @if(session('success'))
        <script>showToast('{{ session('success') }}', 'success');</script>
    @endif
    
    @if(session('error'))
        <script>showToast('{{ session('error') }}', 'error');</script>
    @endif
    
    @stack('scripts')
</body>
</html>
