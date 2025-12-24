<!-- resources/views/tenant/mikrotik/tutorial.blade.php -->

@extends('layouts.nologin')
@section('title', 'Tutorial: Aktifkan 2FA & Buat App Password')
@section('page-title', '🔐 Cara Atur Notifikasi Email via Gmail')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            Cara Aktifkan 2-Step Verification & Buat App Password
        </h1>

        <p class="text-gray-600 mb-6 text-center">
            Ikuti langkah-langkah berikut untuk mengatur Gmail agar bisa mengirim notifikasi dari aplikasi.
        </p>

        <ol class="list-decimal list-inside space-y-5 text-gray-700">
            <li>
                <strong>Buka halaman akun Google:</strong>
                <a href="https://myaccount.google.com" target="_blank" class="text-blue-600 hover:underline font-medium">
                    https://myaccount.google.com
                </a>
                <p class="text-sm text-gray-500 mt-1">Gunakan email yang ingin digunakan untuk kirim notifikasi.</p>
            </li>

            <li>
                <strong>Klik menu <span class="font-medium">Security</span> (Keamanan):</strong>
                <a href="https://myaccount.google.com/security" target="_blank" class="text-blue-600 hover:underline font-medium">
                    https://myaccount.google.com/security
                </a>
                <p class="text-sm text-gray-500 mt-1">Biasanya di bawah bagian "Sign in to Google".</p>
            </li>

            <li>
                <strong>Aktifkan <span class="font-medium">2-Step Verification (Verifikasi 2 Langkah)</span></strong>
                <p class="text-sm text-gray-500 mt-1">
                    Ikuti proses verifikasi (bisa pakai SMS, Google Authenticator, dll).
                </p>
            </li>

            <li class="bg-yellow-50 p-3 rounded-lg border-l-4 border-yellow-400">
                <strong>Setelah 2FA aktif, cari bagian:</strong>
                <div class="font-medium text-yellow-800 mt-1">🔐 <a href="https://myaccount.google.com/apppasswords" target="_blank" class="underline hover:text-yellow-600">App passwords</a></div>
                <p class="text-sm text-yellow-700 mt-1">
                    🔗 <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-blue-600 underline">
                        Klik di sini untuk langsung ke App Password
                    </a>
                </p>
            </li>


            <li>
                <strong>Masukkan nama: <span class="font-mono bg-gray-100 px-2 py-1 rounded">Billing App</span></strong>
                <p class="text-sm text-gray-500 mt-1">Kamu bisa ganti dengan nama ISP-mu.</p>
            </li>

            <li class="bg-green-50 p-3 rounded-lg border-l-4 border-green-400">
                <strong>Klik <span class="font-medium">Generate / Buat</span></strong>
                <p class="text-sm text-green-700 mt-1">
                    Kamu akan melihat <strong>16-digit kode</strong> (contoh: <code class="bg-white px-1 rounded">abcd efgh ijkl mnop</code>)
                </p>
            </li>

            <li>
                <strong>Salin kode tersebut dan tempel di form aplikasi</strong>
                <p class="text-sm text-red-600 mt-1 font-medium">
                    ⚠️ Kode hanya muncul <u>sekali</u>! Jika tertutup, klik "Back" lalu generate ulang.
                </p>
            </li>
        </ol>

        <div class="mt-8 p-5 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">💡 Tips Penting</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                {{-- <li>• Gunakan email khusus bisnis, misal: <code class="bg-white px-1 rounded">billing@tokonet.id</code></li> --}}
                <li>• App Password hanya untuk mengirim pesan melalui pihak ketiga, tidak bisa digunakan login ke applikasi <strong>Gmail</strong> </li>
                <li>• Simpan App Password dengan aman</li>
                <li>• Jika App Password tidak muncul, pastikan 2FA sudah aktif</li>
            </ul>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                &larr; Kembali
            </a>
        </div>
    </div>
@endsection