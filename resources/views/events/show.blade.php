@extends('layouts.dashboard')

@section('page-title', 'Event Details')

@section('page-content')
<div class="space-y-6" x-data="{ showMarkAttendanceModal: false, selectedMemberId: null }">
    <!-- Back Button -->
    <div>
        <a href="{{ route('events.index') }}" class="inline-flex items-center space-x-2 text-gray-600 hover:text-gray-900">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Events</span>
        </a>
    </div>
    
    <!-- Event Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <span class="inline-flex px-3 py-1 text-xs rounded-full {{ $event->status === 'Upcoming' ? 'bg-blue-100 text-blue-700' : ($event->status === 'Ongoing' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ $event->status }}
                    </span>
                    <span class="text-sm text-gray-600">{{ $event->category }}</span>
                </div>
                <h1 class="text-gray-900 mb-3">{{ $event->title }}</h1>
                <p class="text-gray-600 mb-4">{{ $event->description }}</p>
                
                <!-- Event Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Date</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->date->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="clock" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Time</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Location</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->location }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('events.edit', $event) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i data-lucide="edit-2" class="w-5 h-5"></i>
                </a>
                <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Registered</p>
                    <p class="text-xl font-bold text-gray-900">{{ $event->registered_count }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Present</p>
                    <p class="text-xl font-bold text-gray-900">{{ $event->present_count }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="target" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Target</p>
                    <p class="text-xl font-bold text-gray-900">{{ $event->target_participants }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="percent" class="w-5 h-5 text-orange-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Attendance Rate</p>
                    <p class="text-xl font-bold text-gray-900">{{ $event->attendance_rate }}%</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Attendance Check-in Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-gray-900 text-lg">Quick Attendance Check-in</h3>
        <p class="text-sm text-gray-600 mb-4">Members can quickly check in by entering their Member ID below</p>
        <form action="{{ route('events.record-attendance', $event) }}" method="POST">
            @csrf
            <div class="flex items-center space-x-4">
                <input 
                    type="text" 
                    name="member_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                    placeholder="Enter Member ID (e.g., 25-00001)" 
                    required
                />
                <button 
                    type="submit" 
                    class="flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Add</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance List - Card View -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-gray-900">Attendance List</h3>
                <p class="text-sm text-gray-600 mt-1">Members registered for this event</p>
            </div>
        </div>

        <div class="px-6 py-5">
            @if($event->attendance->isEmpty())
                <div class="py-12 text-center text-gray-400">
                    <i data-lucide="users" class="w-12 h-12 mx-auto mb-3"></i>
                    <p class="text-sm">No attendance records yet</p>
                </div>
            @else
                <!-- Card grid -->
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($event->attendance as $record)
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">

                            <!-- Header: Avatar + name -->
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white text-lg font-semibold">
                                    {{ strtoupper(substr($record->member->name, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="text-base font-semibold text-gray-900">
                                        {{ $record->member->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $record->member->member_id }}
                                    </p>
                                </div>
                            </div>

                            <!-- Body: location + time -->
                            <div class="mt-4 space-y-3 text-sm text-gray-600">
                                <div class="flex items-center space-x-2">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-gray-400"></i>
                                    <span>
                                        {{ $record->member->purok ?? $record->member->address ?? 'No location' }}
                                    </span>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-400"></i>
                                    <span>
                                        @if($record->check_in_time)
                                            {{ $record->check_in_time->format('Y-m-d h:i A') }}
                                        @else
                                            Not yet checked in
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Footer: status + remarks -->
                            <div class="mt-5 flex items-center justify-between">

                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                    @if($record->status === 'Present')
                                        bg-green-100 text-green-700
                                    @elseif($record->status === 'Absent')
                                        bg-red-100 text-red-700
                                    @elseif($record->status === 'Late')
                                        bg-yellow-100 text-yellow-700
                                    @else
                                        bg-blue-100 text-blue-700
                                    @endif
                                ">
                                    @if($record->status === 'Present')
                                        ✓ Attended
                                    @else
                                        {{ $record->status }}
                                    @endif
                                </span>

                                @if($record->remarks)
                                    <p class="text-xs text-gray-500 truncate max-w-[150px]">
                                        {{ $record->remarks }}
                                    </p>
                                @endif

                            </div>

                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </div>
    
    <!-- Mark Attendance Modal -->
    <div 
        x-show="showMarkAttendanceModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="absolute inset-0 bg-black/40" @click="showMarkAttendanceModal = false"></div>
        
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="mb-6">
                <h3 class="text-gray-900 mb-1">Mark Attendance</h3>
                <p class="text-sm text-gray-600">Select member and attendance status</p>
            </div>
            
            <form action="{{ route('events.attendance', $event) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Member <span class="text-red-500">*</span></label>
                    <select name="member_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->member_id }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Excused">Excused</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Remarks (Optional)</label>
                    <textarea name="remarks" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add any additional notes..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button
                        type="button"
                        @click="showMarkAttendanceModal = false"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Mark Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
