<div x-data="{ show: false, message: '', type: 'success' }"
     x-init="
        @if(session('success'))
            setTimeout(() => { show = true; message = '{{ session('success') }}'; type = 'success'; }, 100);
            setTimeout(() => { show = false; }, 5000);
        @elseif(session('error'))
            setTimeout(() => { show = true; message = '{{ session('error') }}'; type = 'error'; }, 100);
            setTimeout(() => { show = false; }, 5000);
        @endif
     "
     x-show="show"
     :class="{
         'bg-green-500': type === 'success',
         'bg-red-500': type === 'error'
     }"
     class="fixed top-4 right-4 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto z-50 transform transition-all duration-300 ease-in-out"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">

    <div class="rounded-lg shadow-xs overflow-hidden">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-start">
                    <svg x-show="type === 'success'" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="type === 'error'" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p x-text="message" class="ml-3 text-sm font-medium text-white"></p>
                </div>
                <button @click="show = false" class="ml-4 flex-shrink-0 text-white focus:outline-none">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>