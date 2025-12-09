<!-- Add Event Modal -->
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
                <h2 class="text-gray-900">Create New Event</h2>
                <p class="text-sm text-gray-600 mt-1">Schedule and organize a new SK event or activity</p>
            </div>
            <button @click="showAddModal = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('events.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Event Details -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-900">Event Details</h3>
                        <p class="text-sm text-gray-600">Basic information about the event</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Event Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Community Clean-up Drive">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" required rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Provide a detailed description of the event"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-2">Event Category <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Category</option>
                                <option value="Community Service">Community Service</option>
                                <option value="Sports & Recreation">Sports & Recreation</option>
                                <option value="Education & Training">Education & Training</option>
                                <option value="Health & Wellness">Health & Wellness</option>
                                <option value="Cultural">Cultural</option>
                                <option value="Social">Social</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-2">Event Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="Upcoming">Upcoming</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-900">Schedule</h3>
                        <p class="text-sm text-gray-600">When will this event take place</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Event Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="map-pin" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-900">Location</h3>
                        <p class="text-sm text-gray-600">Where will this event be held</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
                        <input type="text" name="location" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Barangay Main Street">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Venue <span class="text-red-500">*</span></label>
                        <input type="text" name="venue" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Barangay Hall">
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-5 h-5 text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-900">Participants</h3>
                        <p class="text-sm text-gray-600">Expected number of attendees</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Target Participants <span class="text-red-500">*</span></label>
                        <input type="number" name="target_participants" required min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="50">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Maximum Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="max_capacity" required min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="100">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-4">
                <button type="button" @click="showAddModal = false" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex items-center space-x-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Create Event</span>
                </button>
            </div>
        </form>
    </div>
</div>
