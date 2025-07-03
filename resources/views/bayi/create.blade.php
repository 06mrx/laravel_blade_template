@extends('layouts.admin')
@section('title', 'Tambah Bayi')
@section('page-title', 'Tambah Data Bayi Baru')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('bayi.store') }}" method="POST">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" id="nama"
                       value="{{ old('nama') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nama bayi" required>
            </div>

            <!-- NIK -->
            <div class="mb-4">
                <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <input type="text" name="nik" id="nik"
                       value="{{ old('nik') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nomor Induk Kependudukan" required>
            </div>

            <!-- Tanggal Lahir -->
            <div class="mb-4">
                <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" id="tgl_lahir"
                       value="{{ old('tgl_lahir') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="jk" value="L" {{ old('jk') == 'L' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600" required>
                        <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="jk" value="P" {{ old('jk') == 'P' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600" required>
                        <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                    </label>
                </div>
            </div>

            <!-- Nama Orang Tua -->
            <div class="mb-4">
                <label for="nama_ortu" class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua / Wali (Ayah / Ibu)</label>
                <input type="text" name="nama_ortu" id="nama_ortu"
                       value="{{ old('nama_ortu') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nama Ayah / Nama Ibu" required>
            </div>

            <!-- Berat Badan -->
            <div class="mb-4">
                <label for="bb" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)</label>
                <input type="number" step="0.1" name="bb" id="bb"
                       value="{{ old('bb') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: 3.5">
            </div>

            <!-- Tinggi Badan -->
            <div class="mb-4">
                <label for="tb" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan (cm)</label>
                <input type="number" step="0.1" name="tb" id="tb"
                       value="{{ old('tb') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: 50.5">
            </div>

            <!-- Lingkar Lengan -->
            <div class="mb-4">
                <label for="ll" class="block text-sm font-medium text-gray-700 mb-1">Lingkar Lengan (cm)</label>
                <input type="number" step="0.1" name="ll" id="ll"
                       value="{{ old('ll') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: 12.5">
            </div>

            <!-- Lingkar Kepala -->
            <div class="mb-4">
                <label for="lk" class="block text-sm font-medium text-gray-700 mb-1">Lingkar Kepala (cm)</label>
                <input type="number" step="0.1" name="lk" id="lk"
                       value="{{ old('lk') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: 35.5">
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label for="ket" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="ket" id="ket" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Catatan tambahan">{{ old('ket') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('bayi.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nikInput = document.getElementById('nik');
            const errorSpan = document.createElement('span');
            errorSpan.id = 'nik-error';
            errorSpan.classList.add('text-red-500', 'text-sm', 'mt-1');
            nikInput.parentNode.appendChild(errorSpan);

            nikInput.addEventListener('input', function () {
                let value = nikInput.value;

                // Hanya izinkan angka
                value = value.replace(/[^0-9]/g, '');
                nikInput.value = value;

                // Validasi panjang harus 16 digit
                if (value.length !== 16 && value.length > 0) {
                    errorSpan.textContent = 'NIK harus terdiri dari 16 digit angka.';
                    nikInput.setCustomValidity('NIK tidak valid');
                } else {
                    errorSpan.textContent = '';
                    nikInput.setCustomValidity('');
                }
            });
        });
    </script>
@endpush