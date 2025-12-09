@extends('layouts.dashboard')

@section('page-title', 'Attendance Management')

@section('page-content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-gray-900 mb-2">Attendance Management</h1>
        <p class="text-gray-600">Track and manage event attendance records</p>
    </div>
    
    <!-- Event Selection -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('attendance.index') }}" method="GET">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm text-gray-700 mb-2">Select Event</label>
                    <select name="event_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Choose an Event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }} - {{ $event->date->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    @if($selectedEvent)
    <!-- Event Details -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-sm p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-white mb-2">{{ $selectedEvent->title }}</h2>
                <div class="flex items-center space-x-6 text-blue-100">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>{{ $selectedEvent->date->format('F d, Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span>{{ $selectedEvent->start_time->format('h:i A') }} - {{ $selectedEvent->end_time->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>{{ $selectedEvent->location }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-blue-100 text-sm">Attendance Rate</p>
                <p class="text-3xl font-bold">{{ $selectedEvent->attendance_rate }}%</p>
            </div>
        </div>
    </div>
    
    <!-- Attendance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Present</p>
                    <p class="text-xl font-bold text-gray-900">{{ $attendance->where('status', 'Present')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Absent</p>
                    <p class="text-xl font-bold text-gray-900">{{ $attendance->where('status', 'Absent')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Late</p>
                    <p class="text-xl font-bold text-gray-900">{{ $attendance->where('status', 'Late')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Excused</p>
                    <p class="text-xl font-bold text-gray-900">{{ $attendance->where('status', 'Excused')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attendance Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Member ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Purok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Check-in Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendance as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($record->member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $record->member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $record->member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 font-mono">{{ $record->member->member_id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $record->member->purok }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ 
                                $record->status === 'Present' ? 'bg-green-100 text-green-700' : 
                                ($record->status === 'Absent' ? 'bg-red-100 text-red-700' : 
                                ($record->status === 'Late' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700'))
                            }}">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">
                                {{ $record->check_in_time ? $record->check_in_time->format('h:i A') : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $record->remarks ?? '-' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i data-lucide="clipboard-list" class="w-12 h-12 mx-auto mb-3"></i>
                                <p class="text-sm">No attendance records for this event</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- No Event Selected -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <i data-lucide="clipboard-list" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
        <h3 class="text-gray-900 mb-2">Select an Event</h3>
        <p class="text-gray-600">Choose an event from the dropdown above to view attendance records</p>
    </div>
    @endif
</div>
@endsection
