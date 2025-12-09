@extends('layouts.dashboard')

@section('page-title', 'Members Management')

@section('page-content')
<div class="space-y-6" 
     x-data="{ showAddModal: false, showDeleteModal: false, memberToDelete: null, deleteUrl: '' }">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-gray-900 mb-2">Members Management</h1>
            <p class="text-gray-600">Manage SK members, view profiles, and track registrations</p>
        </div>
        <button 
            @click="showAddModal = true"
            class="flex items-center space-x-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Member</span>
        </button>
    </div>
    
        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form 
            method="GET" 
            action="{{ route('members.index') }}" 
            id="filter-form"
            class="grid grid-cols-1 md:grid-cols-4 gap-4"
        >
            <!-- Search -->
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, email, or ID..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>
            
            <!-- Role Filter -->
            <div>
                <select 
                    name="role"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All Roles</option>
                    {{-- enum values are "Member" and "Officer" --}}
                    <option value="Member" {{ request('role') === 'Member' ? 'selected' : '' }}>Member</option>
                    <option value="Officer" {{ request('role') === 'Officer' ? 'selected' : '' }}>Officer</option>
                </select>
            </div>

            <!-- Purok Filter (optional but your controller supports it)
            <div>
                <select 
                    name="purok"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All Puroks</option>
                    @foreach($puroks ?? [] as $purok)
                        <option value="{{ $purok }}" {{ request('purok') === $purok ? 'selected' : '' }}>
                            {{ $purok }}
                        </option>
                    @endforeach
                </select>
            </div>-->

            <!-- Export Button -->
            <div>
                <a href="{{ route('members.export') }}" 
                class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </form>
    </div>
    {{-- Optional: auto-submit on change --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('filter-form');
            ['role', 'purok'].forEach(name => {
                const el = form.querySelector(`[name="${name}"]`);
                if (el) el.addEventListener('change', () => form.submit());
            });
        });
    </script>

    <!-- Members Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Member ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Purok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($members as $member)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 font-mono">{{ $member->member_id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $member->purok }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $member->age }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $member->role === 'Officer' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $member->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($member->date_joined)->format('F j, Y') }}
                            </span>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('members.edit', $member->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                <button 
                                    @click="
                                        showDeleteModal = true; 
                                        memberToDelete = {{ $member->id }};
                                        deleteUrl = '{{ route('members.destroy', $member->id) }}'
                                    "
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                >
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3"></i>
                                <p class="text-sm">No members found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $members->links() }}
        </div>
        @endif
    </div>
    
    <!-- Add Member Modal -->
    @include('members.partials.add-modal')
    
    <!-- Delete Confirmation Modal -->
    @include('components.modals.delete-confirmation', [
        'title' => 'Delete Member',
        'message' => 'Are you sure you want to delete this member? This action cannot be undone.',
        'route' => 'members.destroy'
    ])

    @if(session('generated_password') && session('generated_email'))
        <div class="mb-4 p-4 rounded bg-green-100 border border-green-300">
            <p class="font-semibold mb-1">Login credentials created:</p>
            <p>Email: <strong>{{ session('generated_email') }}</strong></p>
            <p>Temporary password: <strong>{{ session('generated_password') }}</strong></p>
            <p class="text-sm text-gray-700 mt-2">
                Please copy or send this password to the member now.
                It will not be shown again after you leave or refresh this page.
            </p>
        </div>
    @endif
</div>
@endsection
