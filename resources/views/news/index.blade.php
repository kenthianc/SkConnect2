@extends('layouts.dashboard')

@section('page-title', 'News & Announcements')

@section('page-content')
<div class="space-y-6" x-data="{ showAddModal: false }">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-gray-900 mb-2">News & Announcements</h1>
            <p class="text-gray-600">Stay updated with the latest SK news and events</p>
        </div>
        <button 
            @click="showAddModal = true"
            class="flex items-center space-x-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Post News</span>
        </button>
    </div>
    
    <!-- News List -->
    <div class="space-y-4">
        @forelse($news as $item)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
                <!-- Icon -->
                <div class="w-12 h-12 {{ $item->type === 'EVENT' ? 'bg-green-500' : ($item->type === 'ANNOUNCEMENT' ? 'bg-blue-500' : ($item->type === 'UPDATE' ? 'bg-orange-500' : 'bg-purple-500')) }} rounded-lg flex items-center justify-center flex-shrink-0">
                    @if($item->type === 'EVENT')
                        <i data-lucide="calendar" class="w-5 h-5 text-white"></i>
                    @elseif($item->type === 'ANNOUNCEMENT')
                        <i data-lucide="megaphone" class="w-5 h-5 text-white"></i>
                    @elseif($item->type === 'UPDATE')
                        <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                    @else
                        <i data-lucide="bell" class="w-5 h-5 text-white"></i>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs {{ $item->type === 'EVENT' ? 'bg-green-500 text-white' : ($item->type === 'ANNOUNCEMENT' ? 'bg-blue-500 text-white' : ($item->type === 'UPDATE' ? 'bg-orange-500 text-white' : 'bg-purple-500 text-white')) }} mb-2">
                                {{ $item->type }}
                            </span>
                            <h3 class="text-gray-900 mb-2">{{ $item->title }}</h3>
                        </div>
                        <form action="{{ route('news.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                        <div class="flex items-center space-x-1">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            <span>{{ $item->author }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>{{ $item->created_at->format('l, F d, Y h:i A') }}</span>
                        </div>
                    </div>

                    <p class="text-gray-600 leading-relaxed">{{ $item->content }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <img src="{{ asset('images/empty_event.png') }}" alt="Empty News" class="w-20 h-20 mx-auto mb-4" />
            <h3 class="text-gray-900 mb-2">No news yet</h3>
            <p class="text-gray-600 mb-4">Start sharing updates with your members</p>
            <button 
                @click="showAddModal = true"
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Post News</span>
            </button>
        </div>
        @endforelse
    </div>
    
    <!-- Add News Modal -->
    @include('news.partials.add-modal')
</div>
@endsection
