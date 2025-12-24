@extends('layouts.admin')
@section('title', 'Buat Konfigurasi')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Konfigurasi Sistem</h1>

        <form action="{{ route('tenant.configuration.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Business Name -->
            <div class="mb-6">
                <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Usaha</label>
                <input type="text" name="business_name" id="business_name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Contoh: PT. Nusantara Jaya" required>
            </div>

            <!-- Business Logo -->
            <div class="mb-6">
                <label for="business_logo" class="block text-sm font-medium text-gray-700 mb-2">Logo Usaha</label>
                <input type="file" name="business_logo" id="business_logo"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" accept="image/*">

                <p class="text-xs text-gray-500 mt-1">Opsional. Max 2MB, format: JPG, PNG</p>

                {{-- Preview gambar --}}
                <div class="mt-3">
                    <img id="logoPreview" src="#" alt="Preview Logo"
                        class="hidden w-32 h-32 object-contain border rounded-lg">
                </div>
            </div>



            <!-- Payment Type -->
            <div class="mb-6">
                <label for="payment_type_id" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select id="payment_type_id" name="payment_type_id" id="payment_type_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    <option value="manual">Manual (Transfer Bank)</option>
                    <option value="midtrans">Midtrans (Otomoatis)</option>
                </select>
            </div>

            <div id="midtrans-key">
                <!-- Midtrans Client Key -->
                <div class="mb-6">
                    <label for="midtrans_client_key" class="block text-sm font-medium text-gray-700 mb-2">Midtrans Client
                        Key</label>
                    <input type="text" name="midtrans_client_key" id="midtrans_client_key"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="contoh: SB-Mid-client-xxxxxx">
                </div>

                <!-- Midtrans Server Key -->
                <div class="mb-6">
                    <label for="midtrans_server_key" class="block text-sm font-medium text-gray-700 mb-2">Midtrans Server
                        Key
                        🔐</label>
                    <input type="password" name="midtrans_server_key" id="midtrans_server_key"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Masukkan Server Key">
                </div>
            </div>

            <!-- Jenis -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Penagihan</label>
                <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                    onchange="toggleDueDays()">
                    <option value="fixed">Fixed (Tanggal Tetap)</option>
                    <option value="segmented">Segmented (Banyak Tanggal)</option>
                    <option value="anniversary">Anniversary (Tanggal Daftar)</option>
                </select>
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div class="mb-6" id="due_days_field">
                <label for="due_days" class="block text-sm font-medium text-gray-700 mb-2" id="due_days_label">Tanggal Jatuh Tempo</label>
                <input type="text" name="due_days" id="due_days"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Contoh: 5">
                <p class="text-xs text-gray-500 mt-1 hidden">Hanya untuk jenis Fixed dan Segmented.</p>
            </div>

          

            <!-- Submit -->
            <div class="flex justify-end space-x-3">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function toggleDueDays() {
            const type = document.getElementById('type').value;
            const field = document.getElementById('due_days_field');
            const filedLabel = document.getElementById('due_days_label');
            const dueDays = document.getElementById('due_days'); // add this line to get the due days
            field.style.display = type === 'anniversary' ? 'none' : 'block';

            if(type == 'segmented') {
                filedLabel.textContent = 'Tanggal Jatuh Tempo (pisahkan dengan koma)';
                dueDays.placeholder = 'Contoh : 10, 20 30';
            } else if(type == 'fixed') {
                filedLabel.textContent = 'Tanggal Jatuh Tempo';
                dueDays.placeholder = 'Contoh : 10';
                dueDays.value = '';
            } else if (type == 'anniversary') {
                filedLabel.textContent = 'Tanggal Jatuh Tempo';
            }
        }
        toggleDueDays(); // init


        const midtransKey = document.getElementById('midtrans-key');
        const paymentType = document.getElementById('payment_type_id');
        // check payment_type_id if "manual"
        function toggleMidtransKey() {
            if (paymentType.value === 'manual') {
                midtransKey.style.display = 'none';
            } else {
                midtransKey.style.display = 'block';
            }
        }

        // Jalankan pertama kali biar kondisi sesuai saat halaman load
        toggleMidtransKey();

        // Jalankan saat dropdown berubah
        paymentType.addEventListener('change', toggleMidtransKey);
        document.getElementById('business_logo').addEventListener('change', function(event) {



            const file = event.target.files[0];
            const preview = document.getElementById('logoPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
            }
        });
    </script>
@endpush
