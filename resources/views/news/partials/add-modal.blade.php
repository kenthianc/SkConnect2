<!-- Add News Modal -->
<div 
    x-show="showAddModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div class="absolute inset-0 bg-black/40" @click="showAddModal = false"></div>
    
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
            <div>
                <h2 class="text-gray-900">Post News & Announcement</h2>
                <p class="text-sm text-gray-600 mt-1">Create a news post and notify all members</p>
            </div>
            <button @click="showAddModal = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('news.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Announcement Details -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="megaphone" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-900">Announcement Details</h3>
                        <p class="text-sm text-gray-600">Select type and provide announcement information</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Announcement Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="ANNOUNCEMENT">Announcement</option>
                            <option value="EVENT">Event</option>
                            <option value="UPDATE">Update</option>
                            <option value="REMINDER">Reminder</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Choose the type that best describes your post</p>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            placeholder="Enter a clear and concise title"
                        >
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="bell" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-900">Content</h3>
                        <p class="text-sm text-gray-600">Write your announcement message</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="content" 
                        required 
                        rows="6" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Write your announcement content here. Be clear and informative..."
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Provide detailed information about your announcement</p>
                </div>
            </div>

            <!-- Notification Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="bell" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                    <div>
                        <h4 class="text-blue-900 mb-1">Member Notification</h4>
                        <p class="text-sm text-blue-700">
                            When you post this announcement, all registered members will be notified via the system. 
                            Make sure your message is clear and contains all necessary information.
                        </p>
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
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span>Post & Notify All Members</span>
                </button>
            </div>
        </form>
    </div>
</div>
