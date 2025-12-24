<!-- resources/views/components/expire-command-modal.blade.php -->
<div x-data="{
    show: false,
    running: false,
    message: '',
    success: false,
    mikrotikId: null,

    runCommand() {
        this.running = true;
        this.message = 'Menjalankan proses...';
        this.success = false;

        axios.post('{{ route('tenant.mikrotik.send-mail-notification') }}', {
            mikrotik_id: this.mikrotikId
        })
        .then(response => {
            this.message = response.data.message;
            this.success = true;
            setTimeout(() => {
                this.show = false;
                location.reload();
            }, 2000);
        })
        .catch(error => {
            this.message = error.response?.data?.message || 'Gagal menjalankan perintah.';
            this.success = false;
        })
        .finally(() => {
            this.running = false;
        });
    }
}"
     @run-expire-command.window="show = true; mikrotikId = $event.detail.mikrotikId"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">

        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" x-show="show" @click.away="show = false" x-transition.opacity>
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <div x-show="show" x-transition
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Jalankan Penanganan Kedaluwarsa</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Proses ini akan:
                            <ul class="list-disc list-inside mt-1">
                                <li>Nonaktifkan pelanggan yang kedaluwarsa</li>
                                <li>Kirim notifikasi (akan & sudah expired)</li>
                                <li>Putus koneksi aktif di MikroTik</li>
                                <li>Sinkron ke RADIUS</li>
                            </ul>
                            Lanjutkan?
                            </p>
                        </div>

                        <!-- Hasil Eksekusi -->
                        <template x-if="message">
                            <div class="mt-4 p-3 text-sm"
                                :class="success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                <span x-text="message"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Form / Aksi -->
            <form @submit.prevent="runCommand" class="px-4 py-3 sm:px-6 sm:flex justify-end space-x-3">
                <button type="button" @click="show = false"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
                <button type="submit" :disabled="running"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:w-auto sm:text-sm"
                    x-text="running ? 'Menjalankan...' : 'Jalankan'">
                </button>
            </form>
        </div>
    </div>

    <!-- Script Alpine -->
    <script>
        document.addEventListener('alpine:init', () => {
            /*
            Alpine.data('expireCommandModal', () => ({
                show: false,
                running: false,
                message: '',
                success: false,
                mikrotikId: null,

                init() {
                    // Terima event dari anywhere
                    this.$watch('show', (value) => {
                        if (value) {
                            // Reset state
                            this.message = '';
                            this.success = false;
                        }
                    });
                },

                runCommand() {
                    this.running = true;
                    this.message = 'Menjalankan proses...';
                    this.success = false;

                    // Ambil mikrotik_id dari tombol atau data
                    const mikrotikId = this.mikrotikId || document.querySelector(
                        '[x-data*="run-expire-command"]').__x.$data.mikrotikId;

                    axios.post('{{ route('tenant.mikrotik.send-mail-notification') }}', {
                            mikrotik_id: mikrotikId
                        })
                        .then(response => {
                            this.message = response.data.message;
                            this.success = true;
                            setTimeout(() => {
                                this.show = false;
                                window.location.reload();
                            }, 2000);
                        })
                        .catch(error => {
                            this.message = error.response?.data?.message ||
                                'Gagal menjalankan perintah.';
                            this.success = false;
                        })
                        .finally(() => {
                            this.running = false;
                        });
                }
            }));
            */
        });

        // Global helper untuk dispatch
        window.runExpireCommand = function(mikrotikId) {
            document.dispatchEvent(new CustomEvent('run-expire-command', {
                detail: {
                    mikrotikId
                }
            }));
        };
    </script>
</div>
