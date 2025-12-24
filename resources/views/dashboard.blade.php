@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e5dbb 0%, #667eea 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .tab-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .tab-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .tab-link.active::after {
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .notification-badge {
            animation: bounce 1s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }
    </style>
@endpush

@section('content')

    <!-- Header with Configuration Business Name -->
    <div class="gradient-bg rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <!-- Tampilkan Nama Usaha dari Configuration -->
                <h1 class="text-3xl font-bold mb-2">{{ $config->business_name }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-white/90">
                    <div class="flex items-center gap-2">

                        {{-- <span class="font-medium">Sistem Manajemen Jaringan</span> --}}
                    </div>
                    {{-- <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-400 rounded-full pulse-dot"></div>
                        <span class="capitalize font-medium">Aktif</span>
                    </div> --}}
                </div>
            </div>

            <!-- Logo dari Configuration -->
            @if ($config->business_logo)
                <div class="flex-shrink-0">
                    <img src="{{ Storage::url($config->business_logo) }}" alt="Logo" class="h-16 rounded-xl shadow-lg">
                </div>
            @else
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    {{-- <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8 ">
        <div class="stat-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M16.67 13.13C18.04 14.06 19 15.32 19 17v3h4v-3c0-2.18-3.57-3.47-6.33-3.87M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4c-.47 0-.91.1-1.33.24a5.98 5.98 0 0 1 0 7.52c.42.14.86.24 1.33.24m-6 0c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0 7c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4m6 5H3v-.99C3.2 16.29 6.3 15 9 15s5.8 1.29 6 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 w-4 h-4" viewBox="0 0 48 48">
                    <g fill="none">
                        <path fill="currentColor" stroke="currentColor" stroke-linejoin="round" stroke-width="4" d="M19 20a7 7 0 1 0 0-14a7 7 0 0 0 0 14Z"/>
                        <path fill="currentColor" fill-rule="evenodd" d="M36 29v12zm-6 6h12z" clip-rule="evenodd"/>
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M36 29v12m-6-6h12m-15-7h-8.2c-4.48 0-6.72 0-8.432.872a8 8 0 0 0-3.496 3.496C6 34.08 6 36.32 6 40.8V42h21"/>
                    </g>
                </svg>
                <span class="font-medium">Total registrasi</span>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Aktif</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($activeCount) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full pulse-dot"></div>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span class="font-medium">Sedang online</span>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Akan Expire</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ number_format($expiringSoon) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor">
                            <path d="M20.5 13.5a8.5 8.5 0 1 1-17 0a8.5 8.5 0 0 1 17 0"/>
                            <path d="M12 19a5.5 5.5 0 1 1 0-11m1.5-4.5a1.5 1.5 0 1 0-3 0a1.5 1.5 0 0 0 3 0m-1.5 10L15 9"/>
                        </g>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-yellow-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M4 19v-2h2v-7q0-2.075 1.25-3.687T10.5 4.2V2h3v2.2q2 .5 3.25 2.113T18 10v7h2v2zm8 3q-.825 0-1.412-.587T10 20h4q0 .825-.587 1.413T12 22m-4-5h8v-7q0-1.65-1.175-2.825T12 6T9.175 7.175T8 10zm-6-7q0-2.5 1.113-4.587T6.1 1.95l1.175 1.6q-1.5 1.1-2.387 2.775T4 10zm18 0q0-2-.888-3.675T16.726 3.55l1.175-1.6q1.875 1.375 2.988 3.463T22 10z"/>
                </svg>
                <span class="font-medium">3 hari ke depan</span>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Expired Hari Ini</p>
                    <p class="text-3xl font-bold text-red-600">{{ number_format($expiredToday) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" viewBox="0 0 16 16">
                        <path fill="currentColor" fill-rule="evenodd" d="M8.175.002a8 8 0 1 0 2.309 15.603a.75.75 0 0 0-.466-1.426a6.5 6.5 0 1 1 3.996-8.646a.75.75 0 0 0 1.388-.569A8 8 0 0 0 8.175.002M8.75 3.75a.75.75 0 0 0-1.5 0v3.94L5.216 9.723a.75.75 0 1 0 1.06 1.06L8.53 8.53l.22-.22zM15 15a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-.25-6.25a.75.75 0 0 0-1.5 0v3.5a.75.75 0 0 0 1.5 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m-1-4h2V7h-2zm1 10.3L8.65 20H4v-4.65L.7 12L4 8.65V4h4.65L12 .7L15.35 4H20v4.65L23.3 12L20 15.35V20h-4.65zm0-2.8l2.5-2.5H18v-3.5l2.5-2.5L18 9.5V6h-3.5L12 3.5L9.5 6H6v3.5L3.5 12L6 14.5V18h3.5zm0-8.5"/>
                </svg>
                <span class="font-medium">Perlu perpanjangan</span>
            </div>
        </div>
    </div> --}}

    <!-- Activity Cards -->
    {{-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">🟢 Online Sekarang</h3>
                <div class="w-3 h-3 bg-green-500 rounded-full pulse-dot"></div>
            </div>
            <p class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($onlineUsersCount) }}</p>
            <p class="text-sm text-gray-600">Pengguna aktif saat ini</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">🕒 Login 24 Jam</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-500" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold text-purple-600 mb-2">1,240</p>
            <p class="text-sm text-gray-600">Session dalam 24 jam terakhir</p>
        </div>
    </div> --}}

    <!-- Informasi Konfigurasi -->
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">⚙️ Informasi Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Nama Usaha</p>
                <p class="text-lg font-semibold text-gray-900">{{ $config->business_name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Metode Pembayaran</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $config->payment_type_id === 'midtrans' ? 'Midtrans (Otomatis)' : 'Manual (Transfer Bank)' }}
                </p>
            </div>
            {{-- <div>
                <p class="text-sm font-medium text-gray-600">Midtrans Client Key</p>
                <p class="text-sm text-gray-500">
                    {{ $config->midtrans_client_key ? 'Terpasang' : 'Belum dikonfigurasi' }}
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Midtrans Server Key</p>
                <p class="text-sm text-gray-500">
                    {{ $config->midtrans_server_key ? 'Terpasang (Terverifikasi)' : 'Belum dikonfigurasi' }}
                </p>
            </div> --}}
            <div>
                <p class="text-sm font-medium text-gray-600">Siklus Pembayaran</p>
                <p class="text-sm text-gray-500">
                    {{ $billingCycle->type ?? 'Belum dikonfigurasi' }}
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Tanggal Jatuh Tenpo</p>
                <p class="text-sm text-gray-500">
                    @if (!empty($billingCycle->dueDays))
                    {{ $billingCycle->due_days ? implode(', ', $billingCycle->due_days) : 'Belum dikonfigurasi' }}
                        
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">⚡ Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @can('view-bankaccount')
                <a href="{{ route('tenant.bank_account.index') }}"
                    class="btn-primary text-white text-center py-3 rounded-xl font-medium hover:opacity-90 transition">
                    ➕ Kelola Akun Bank
                </a>
            @endcan
            @can('view-mikrotik')
                <a href="{{ route('tenant.mikrotik.index') }}"
                    class="bg-green-600 text-white text-center py-3 rounded-xl font-medium hover:bg-green-700 transition">
                    📡 Kelola MikroTik
                </a>
            @endcan

            <a href="{{ route('tenant.configuration.edit', $config->id ?? '#') }}"
                class="bg-indigo-600 text-white text-center py-3 rounded-xl font-medium hover:bg-indigo-700 transition">
                🔧 Pengaturan Sistem
            </a>
        </div>
    </div>

@endsection
