@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('page-content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div>
        <h1 class="text-gray-900 mb-2">Welcome back, {{ Auth::user()->name ?? 'Admin' }}! 👋</h1>
        <p class="text-gray-600">Here's what's happening with your SK organization today</p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">+12%</span>
            </div>
            <h3 class="text-gray-600 text-sm mb-1">Total Members</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalMembers ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $newMembersThisMonth }} new this month</p>
        </div>
        
        <!-- Active Events Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6 text-green-600"></i>
                </div>
                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">{{ $upcomingEvents ?? 0 }} upcoming</span>
            </div>
            <h3 class="text-gray-600 text-sm mb-1">Active Events</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalEvents ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $completedEvents ?? 0 }} completed</p>
        </div>
        
        <!-- Attendance Rate Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clipboard-check" class="w-6 h-6 text-purple-600"></i>
                </div>
                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Excellent</span>
            </div>
            <h3 class="text-gray-600 text-sm mb-1">Attendance Rate</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $attendanceRate ?? 85 }}%</p>
            <p class="text-xs text-gray-500 mt-2">Average across all events</p>
        </div>
        
        <!-- Announcements Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="megaphone" class="w-6 h-6 text-orange-600"></i>
                </div>
                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">{{ $activeNews ?? 0 }} active</span>
            </div>
            <h3 class="text-gray-600 text-sm mb-1">Announcements</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalNews ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Latest updates posted</p>
        </div>
    </div>
    
    <!-- Charts Section 
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
     Member Growth Chart 
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-gray-900">Member Growth</h3>
                <p class="text-sm text-gray-600 mt-1">Monthly registration trends</p>
            </div>
        </div>
        <div class="h-96 flex items-center justify-center text-gray-400">
             Chart.js Canvas 
            <canvas id="memberGrowthChart"></canvas>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Fetch the data from the backend
                fetch('/api/member-growth')
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('memberGrowthChart').getContext('2d');

                        // Create a Chart.js chart
                        new Chart(ctx, {
                            type: 'line', // 'bar', 'pie', or 'line'
                            data: {
                                labels: data.months, // The months from the backend
                                datasets: [{
                                    label: 'Member Registrations',
                                    data: data.counts, // The number of members per month
                                    borderColor: 'rgb(75, 192, 192)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true,
                                }]
                            },
                            options: {
                                responsive: true,  // Ensure responsiveness
                                maintainAspectRatio: false,  // Allow chart to fill container's height
                                scales: {
                                    x: {
                                        title: { display: true, text: 'Month' }
                                    },
                                    y: {
                                        title: { display: true, text: 'Number of Members' },
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching member growth data:', error));
            });
        </script>
    </div>

     Purok Distribution Chart 
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-gray-900">Purok Distribution</h3>
                <p class="text-sm text-gray-600 mt-1">Members by location</p>
            </div>
        </div>
        <div class="h-128 flex items-center justify-center text-gray-400">
             Placeholder for Pie chart 
            <div class="text-center">
                <i data-lucide="pie-chart" class="w-16 h-16 mx-auto mb-2"></i>
                <p class="text-sm">Pie chart visualization here</p>
            </div>
        </div>
    </div>
</div>-->

    
    <!-- Recent Activity & Upcoming Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-gray-900">Recent Members</h3>
                <a href="{{ route('members.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
            </div>
            <div class="space-y-4">
                @forelse($recentMembers ?? [] as $member)
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                        <p class="text-xs text-gray-500">{{ $member->purok }} • Joined {{ $member->date_joined }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">{{ $member->role }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No recent members</p>
                @endforelse
            </div>
        </div>
        
        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-gray-900">Upcoming Events</h3>
                <a href="{{ route('events.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
            </div>
            <div class="space-y-4">
                @forelse($upcomingEventsList as $event)
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="calendar-days" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $event->title }}</p>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}
                            at
                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $event->location }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No upcoming events</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
