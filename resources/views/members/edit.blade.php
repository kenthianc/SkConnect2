@extends('layouts.dashboard')

@section('page-title', 'Edit Member')

@section('page-content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('members.index') }}" class="inline-flex items-center space-x-2 text-gray-600 hover:text-gray-900">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Members</span>
        </a>
    </div>
    
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-gray-900 mb-2">Edit Member</h1>
        <p class="text-gray-600">Update member information</p>
    </div>
    
    <!-- Edit Form -->
    <form action="{{ route('members.update', $member) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-gray-900">Personal Information</h3>
                    <p class="text-sm text-gray-600">Basic details of the member</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                    @error('first_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                    @error('last_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Birthdate <span class="text-red-500">*</span></label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $member->birthdate->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Age <span class="text-red-500">*</span></label>
                    <input type="number" name="age" value="{{ old('age', $member->age) }}" required min="15" max="30" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $member->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $member->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $member->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="mail" class="w-5 h-5 text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-gray-900">Contact Information</h3>
                    <p class="text-sm text-gray-600">How to reach the member</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone', $member->phone) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="map-pin" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-gray-900">Address Information</h3>
                    <p class="text-sm text-gray-600">Location details of the member</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Purok <span class="text-red-500">*</span></label>
                    <select name="purok" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Purok</option>
                        <option value="Purok 1" {{ old('purok', $member->purok) === 'Purok 1' ? 'selected' : '' }}>Purok 1</option>
                        <option value="Purok 2" {{ old('purok', $member->purok) === 'Purok 2' ? 'selected' : '' }}>Purok 2</option>
                        <option value="Purok 3" {{ old('purok', $member->purok) === 'Purok 3' ? 'selected' : '' }}>Purok 3</option>
                        <option value="Purok 4" {{ old('purok', $member->purok) === 'Purok 4' ? 'selected' : '' }}>Purok 4</option>
                        <option value="Purok 5" {{ old('purok', $member->purok) === 'Purok 5' ? 'selected' : '' }}>Purok 5</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Complete Address <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address', $member->address) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Guardian Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-orange-600"></i>
                </div>
                <div>
                    <h3 class="text-gray-900">Guardian Information</h3>
                    <p class="text-sm text-gray-600">Emergency contact person</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Guardian Name <span class="text-red-500">*</span></label>
                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $member->guardian_name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Guardian Contact <span class="text-red-500">*</span></label>
                    <input type="tel" name="guardian_contact" value="{{ old('guardian_contact', $member->guardian_contact) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Role -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="text-gray-900">Member Role</h3>
                    <p class="text-sm text-gray-600">Assign role and status</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Member" {{ old('role', $member->role) === 'Member' ? 'selected' : '' }}>Member</option>
                        <option value="Officer" {{ old('role', $member->role) === 'Officer' ? 'selected' : '' }}>Officer</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('members.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="flex items-center space-x-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Update Member</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
