@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('components.sidebar')
    
    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 overflow-hidden">
        <!-- Top Navbar -->
        @include('components.navbar')
        
        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            @yield('page-content')
        </main>
    </div>
    
    <!-- AI Chatbot -->
    @include('components.chatbot')
</div>
@endsection