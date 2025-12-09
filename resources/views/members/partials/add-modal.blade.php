<!-- Add Member Modal -->
<div 
    x-show="showAddModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div class="absolute inset-0 bg-black/40" @click="showAddModal = false"></div>
    
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
            <div>
                <h2 class="text-gray-900">Add New Member</h2>
                <p class="text-sm text-gray-600 mt-1">Fill in the information below to register a new SK member</p>
            </div>
            <button
                @click="showAddModal = false"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('members.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Personal Information -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
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
                        <input type="text" name="first_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Juan">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Santos">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Dela Cruz">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Birthdate <span class="text-red-500">*</span></label>
                        <input type="date" name="birthdate" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Age <span class="text-red-500">*</span></label>
                        <input type="number" name="age" required min="15" max="30" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="18">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
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
                        <input type="email" name="email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="juan.delacruz@example.com">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+63 912 345 6789">
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
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
                            <option value="Purok 1">Purok 1</option>
                            <option value="Purok 2">Purok 2</option>
                            <option value="Purok 3">Purok 3</option>
                            <option value="Purok 4">Purok 4</option>
                            <option value="Purok 5">Purok 5</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Complete Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="House No., Street Name">
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
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
                        <input type="text" name="guardian_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Parent or Guardian Name">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Guardian Contact <span class="text-red-500">*</span></label>
                        <input type="tel" name="guardian_contact" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+63 912 345 6789">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-4">
                <button
                    type="button"
                    @click="showAddModal = false"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="flex items-center space-x-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Save Member</span>
                </button>
            </div>
        </form>
    </div>
</div>
