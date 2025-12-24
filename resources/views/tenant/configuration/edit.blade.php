@extends('layouts.admin')
@section('title', 'Edit Konfigurasi Sistem')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Konfigurasi Sistem</h1>

        <form action="{{ route('tenant.configuration.update', $config->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Business Name -->
            <div class="mb-6">
                <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Usaha</label>
                <input type="text" name="business_name" id="business_name"
                    value="{{ old('business_name', $config->business_name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Contoh: PT. Nusantara Jaya" required>
            </div>

            <!-- Business Logo -->
            <div class="mb-6">
                <label for="business_logo" class="block text-sm font-medium text-gray-700 mb-2">Logo Usaha</label>
                <input type="file" name="business_logo" id="business_logo"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" accept="image/*">

                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah.</p>

                <!-- Preview gambar -->
                <div class="mt-3">
                    @if($config->business_logo)
                        <div class="mb-2">
                            <img src="{{ Storage::url($config->business_logo) }}" alt="Logo Saat Ini" class="w-32 h-32 object-contain border rounded-lg">
                        </div>
                    @endif
                    <img id="logoPreview" src="#" alt="Preview Logo"
                        class="hidden w-32 h-32 object-contain border rounded-lg">
                </div>
            </div>

            <!-- Payment Type -->
            <div class="mb-6">
                <label for="payment_type_id" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select id="payment_type_id" name="payment_type_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    <option value="manual" {{ $config->payment_type_id === 'manual' ? 'selected' : '' }}>
                        Manual (Transfer Bank)
                    </option>
                    <option value="midtrans" {{ $config->payment_type_id === 'midtrans' ? 'selected' : '' }}>
                        Midtrans (Otomatis)
                    </option>
                </select>
            </div>

            <!-- Midtrans Keys -->
            <div id="midtrans-key" style="{{ $config->payment_type_id === 'manual' ? 'display: none;' : 'display: block;' }}">
                <!-- Midtrans Client Key -->
                <div class="mb-6">
                    <label for="midtrans_client_key" class="block text-sm font-medium text-gray-700 mb-2">Midtrans Client Key</label>
                    <input type="text" name="midtrans_client_key" id="midtrans_client_key"
                        value="{{ old('midtrans_client_key', $config->midtrans_client_key) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="contoh: SB-Mid-client-xxxxxx">
                </div>

                <!-- Midtrans Server Key -->
                <div class="mb-6">
                    <label for="midtrans_server_key" class="block text-sm font-medium text-gray-700 mb-2">Midtrans Server Key 🔐</label>
                    <input type="password" name="midtrans_server_key" id="midtrans_server_key"
                        value="{{ old('midtrans_server_key') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="Kosongkan jika tidak ingin mengubah">
                    <p class="text-xs text-gray-500 mt-1">
                        🔐 Server Key tidak ditampilkan untuk keamanan. Isi hanya jika ingin mengganti.
                    </p>
                </div>
            </div>

            <!-- Jenis Penagihan -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Penagihan</label>
                <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                    onchange="toggleDueDays()">
                    <option value="fixed" {{ $billingCycle->type === 'fixed' ? 'selected' : '' }}>Fixed (Tanggal Tetap)</option>
                    <option value="segmented" {{ $billingCycle->type === 'segmented' ? 'selected' : '' }}>Segmented (Banyak Tanggal)</option>
                    <option value="anniversary" {{ $billingCycle->type === 'anniversary' ? 'selected' : '' }}>Anniversary (Tanggal Daftar)</option>
                </select>
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div class="mb-6" id="due_days_field" style="{{ $billingCycle->type === 'anniversary' ? 'display: none;' : 'display: block;' }}">
                <label for="due_days" class="block text-sm font-medium text-gray-700 mb-2" id="due_days_label">
                    @if($billingCycle->type === 'segmented')
                        Tanggal Jatuh Tempo (pisahkan dengan koma)
                    @else
                        Tanggal Jatuh Tempo
                    @endif
                </label>
                <input type="text" name="due_days" id="due_days"
                    value="{{ old('due_days', is_array($billingCycle->due_days) ? implode(',', $billingCycle->due_days) : $billingCycle->due_days) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                    placeholder="{{ $billingCycle->type === 'segmented' ? 'Contoh: 10,20,30' : 'Contoh: 5' }}">
                <p class="text-xs text-gray-500 mt-1">Hanya untuk jenis Fixed dan Segmented.</p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3">
                <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Perbarui
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
            const label = document.getElementById('due_days_label');
            const input = document.getElementById('due_days');

            if (type === 'anniversary') {
                field.style.display = 'none';
                label.textContent = 'Tanggal Jatuh Tempo';
                input.placeholder = '';
            } else {
                field.style.display = 'block';
                if (type === 'segmented') {
                    label.textContent = 'Tanggal Jatuh Tempo (pisahkan dengan koma)';
                    input.placeholder = 'Contoh: 10,20,30';
                    input.value = '';
                } else {
                    label.textContent = 'Tanggal Jatuh Tempo';
                    input.placeholder = 'Contoh: 5';
                    input.value = '';
                }
            }
        }

        // Inisialisasi saat halaman load
        // toggleDueDays();
        const billingType = document.getElementById('type');
        billingType.addEventListener('change', toggleDueDays);

        // Toggle Midtrans Key
        const midtransKey = document.getElementById('midtrans-key');
        const paymentType = document.getElementById('payment_type_id');

        function toggleMidtransKey() {
            midtransKey.style.display = paymentType.value === 'manual' ? 'none' : 'block';
        }

        paymentType.addEventListener('change', toggleMidtransKey);
        toggleMidtransKey(); // init

        // Preview Logo
        document.getElementById('business_logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('logoPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
            }
        });
    </script>
@endpush