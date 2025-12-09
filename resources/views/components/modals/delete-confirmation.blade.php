<!-- Reusable Delete Confirmation Modal -->
<div 
    x-show="showDeleteModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div class="absolute inset-0 bg-black/40" @click="showDeleteModal = false"></div>
    
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
            </div>
            <div>
                <h3 class="text-gray-900">{{ $title ?? 'Confirm Delete' }}</h3>
                <p class="text-sm text-gray-600">{{ $message ?? 'Are you sure you want to delete this item?' }}</p>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 mt-6">
            <button 
                @click="showDeleteModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancel
            </button>
            <form :action="deleteUrl" method="POST">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
