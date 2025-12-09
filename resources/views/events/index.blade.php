@extends('layouts.dashboard')

@section('page-title', 'Events Management')

@section('page-content')
<div class="space-y-6" x-data="{ showAddModal: false }">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-gray-900 mb-2">Events Management</h1>
            <p class="text-gray-600">Organize and manage SK events and activities</p>
        </div>
        <button 
            @click="showAddModal = true"
            class="flex items-center space-x-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Create Event</span>
        </button>
    </div>
    
    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Event Header -->
            <div class="p-6 pb-4">
                <div class="flex items-start justify-between mb-4">
                    <span class="inline-flex px-3 py-1 text-xs rounded-full {{ $event->status === 'Upcoming' ? 'bg-blue-100 text-blue-700' : ($event->status === 'Ongoing' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ $event->status }}
                    </span>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2 text-gray-400 hover:bg-gray-100 rounded-lg">
                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                            <a href="{{ route('events.show', $event->id) }}" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                <span>View Details</span>
                            </a>
                            <a href="{{ route('events.edit', $event->id) }}" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                                <span>Edit Event</span>
                            </a>
                            <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-gray-900 mb-2">{{ $event->title }}</h3>
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $event->description }}</p>
                
                <!-- Event Info -->
                <div class="space-y-2">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('l, F j, Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span>
                            {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                            -
                            {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Event Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <span class="text-xs text-gray-600">{{ $event->category }}</span>
                <a href="{{ route('events.show', $event->id) }}"
                class="inline-block bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium px-4 py-2 rounded">
                    Mark Attendance
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <img src="{{ asset('images/empty_event.png') }}" alt="Empty Event" class="w-20 h-20 mx-auto mb-4" />
            <h3 class="text-gray-900 mb-2">No events yet</h3>
            <p class="text-gray-600 mb-4">Get started by creating your first event</p>
            <button 
                @click="showAddModal = true"
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Create Event</span>
            </button>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($events->hasPages())
    <div class="flex justify-center">
        {{ $events->links() }}
    </div>
    @endif
    
    <!-- Add Event Modal -->
    @include('events.partials.add-modal')
</div>
@endsection
