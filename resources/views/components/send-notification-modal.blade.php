<!-- resources/views/components/send-notification-modal.blade.php -->

<div x-data="{
    open: false,
    customerId: null,
    mikrotikId: null,
    type: null,
    email: '',
    name: '',
    message: '',
    success: false,
    error: null,
    sending: false,

    init() {
        $watch('open', () => {
            if (this.open) this.reset();
        });

        $on('send-notification', (data) => {
            this.customerId = data.customerId;
            this.mikrotikId = data.mikrotikId;
            this.type = data.type;
            this.email = data.email;
            this.name = data.name;
            this.message = this.getMessage();
            this.open = true;
        });
    },

    reset() {
        this.success = false;
        this.error = null;
        this.sending = false;
    },

    getMessage() {
        if (this.type === 'expiring_soon') {
            return `Halo ${this.name}, akun Anda akan kedaluwarsa dalam 3 hari. Silakan perpanjang segera.`;
        }
        return `Halo ${this.name}, akun Anda telah kedaluwarsa. Silakan hubungi admin untuk perpanjangan.`;
    },

    send() {
        this.sending = true;
        this.success = false;
        this.error = null;

        axios.post('/tenant/notifications/send', {
            customer_id: this.customerId,
            mikrotik_id: this.mikrotikId,
            type: this.type
        })
        .then(response => {
            this.success = true;
            setTimeout(() => { this.open = false; }, 2000);
        })
        .catch(error => {
            this.error = error.response?.data?.message || 'Gagal kirim email.';
        })
        .finally(() => {
            this.sending = false;
        });
    }
}" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>

        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-medium text-gray-900">Kirim Notifikasi Email</h3>

            <p class="mt-2 text-sm text-gray-600">
    <strong>To:</strong> <span x-text="email"></span><br>
    <strong>Nama:</strong> <span x-text="name"></span>
</p>

<div class="mt-4 p-3 bg-gray-50 rounded text-sm">
    <span x-text="message"></span>
</div>

            <template x-if="success">
                <div class="mt-4 p-3 bg-green-100 text-green-800 rounded text-sm">
                    ✅ Email berhasil dikirim!
                </div>
            </template>

            <template x-if="error">
                <div class="mt-4 p-3 bg-red-100 text-red-800 rounded text-sm">
                    ❌ Gagal: <span x-text="error"></span>
                </div>
            </template>

            <div class="mt-6 flex justify-end space-x-3">
                <button @click="open = false" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700">
                    Batal
                </button>
                <button @click="send" :disabled="sending" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" x-text="sending ? 'Mengirim...' : 'Kirim'">
                </button>
            </div>
        </div>
    </div>
</div>