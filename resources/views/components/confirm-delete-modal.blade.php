<div x-data="{ show: false, actionUrl: null, name: '' }"
     @open-delete-modal.window="show = true; actionUrl = $event.detail.url; name = $event.detail.name"
     class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-show="show">
    
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">

        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" x-show="show" @click.away="show = false" x-transition.opacity>
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <div x-show="show" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Role</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete  <strong x-text="name"></strong>? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Delete -->
            <form :action="actionUrl" method="POST" class="px-4 py-3 sm:px-6 sm:flex justify-end space-x-3">
                @csrf
                @method('DELETE')

                <button type="button" @click="show = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>