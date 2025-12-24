@extends('layouts.admin')

@section('title', 'MikroTik: ' . $mikrotik->name)
@section('page-title', $mikrotik->name)

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

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
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
    <!-- Enhanced Header with Gradient Background -->
    <div class="gradient-bg rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 hidden md:block bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white"
                            viewBox="0 0 24 24"><!-- Icon from Material Design Icons by Pictogrammers - https://github.com/Templarian/MaterialDesign/blob/master/LICENSE -->
                            <path fill="currentColor"
                                d="m21 3.1l-.8.8C19 2.8 17.5 2.2 16 2.2s-3 .6-4.2 1.7l-.8-.8C12.4 1.7 14.2 1 16 1s3.6.7 5 2.1m-5 .2c1.2 0 2.4.5 3.3 1.4l-.8.8c-.7-.7-1.6-1-2.5-1s-1.8.3-2.5 1l-.8-.8c.9-.9 2.1-1.4 3.3-1.4m1 6.7h2c.53 0 1.04.21 1.41.59c.38.37.59.88.59 1.41v2c0 .53-.21 1.04-.59 1.41c-.37.38-.88.59-1.41.59h-6v2h1c.55 0 1 .45 1 1h7v2h-7c0 .55-.45 1-1 1h-4c-.55 0-1-.45-1-1H2v-2h7c0-.55.45-1 1-1h1v-2H5c-.53 0-1.04-.21-1.41-.59C3.21 15.04 3 14.53 3 14v-2c0-.53.21-1.04.59-1.41c.37-.38.88-.59 1.41-.59h10V6h2zM5 14h2v-2H5zm3.5 0h2v-2h-2zm3.5 0h2v-2h-2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $mikrotik->name }}</h1>
                        <span class="bg-white/50 p-1 rounded-lg text-xs md:text-md"> {{ $mikrotik->id  }} </span>
                        <div class="flex flex-wrap items-center gap-0 md:gap-4 text-white/90">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                    viewBox="0 0 14 14"><!-- Icon from Streamline by Streamline - https://creativecommons.org/licenses/by/4.0/ -->
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9.5L.5 7L3 4.5m8 5L13.5 7L11 4.5" />
                                        <circle cx="9" cy="7" r=".5" />
                                        <circle cx="5" cy="7" r=".5" />
                                    </g>
                                </svg>
                                <span class="font-medium">{{ $mikrotik->ip_address }}:{{ $mikrotik->port }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-3 h-3 rounded-full ml-2 md:ml-0 {{ $mikrotik->status === 'active' ? 'bg-green-400 pulse-dot' : 'bg-red-400' }}">
                                </div>
                                <span class="capitalize font-medium">{{ $mikrotik->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons with Better Styling -->
            <div class="flex flex-wrap gap-3 justify-end">
                {{-- <button onclick="testConnection('{{ $mikrotik->id }}')"
                    class="px-3 py-2 text-xs bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-all duration-300 font-medium flex items-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:rotate-45 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12L3 5l1.41-1.41L10 9.17l5.59-5.58L17 5l-7 7z"/>
                    </svg>
                    Test Connection
                </button> --}}

                <button onclick="syncData('customers')"
                    class="px-3 py-2 text-xs bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-all duration-300 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                    </svg>
                    Sync Customers
                </button>

                <button onclick="syncData('ippools')"
                    class="px-3 py-2 text-xs bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-all duration-300 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                    </svg>
                    Sync IP Pools
                </button>

                <a href="{{ route('tenant.mikrotik.edit', $mikrotik) }}"
                    class="px-3 py-2 text-xs bg-white text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600"
                        viewBox="0 0 24 24"><!-- Icon from Google Material Icons by Material Design Authors - https://github.com/material-icons/material-icons/blob/master/LICENSE -->
                        <path fill="currentColor"
                            d="M16.67 13.13C18.04 14.06 19 15.32 19 17v3h4v-3c0-2.18-3.57-3.47-6.33-3.87M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4c-.47 0-.91.1-1.33.24a5.98 5.98 0 0 1 0 7.52c.42.14.86.24 1.33.24m-6 0c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0 7c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4m6 5H3v-.99C3.2 16.29 6.3 15 9 15s5.8 1.29 6 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 w-4 h-4"
                    viewBox="0 0 48 48"><!-- Icon from IconPark Solid by ByteDance - https://github.com/bytedance/IconPark/blob/master/LICENSE -->
                    <g fill="none">
                        <path fill="currentColor" stroke="currentColor" stroke-linejoin="round" stroke-width="4"
                            d="M19 20a7 7 0 1 0 0-14a7 7 0 0 0 0 14Z" />
                        <path fill="currentColor" fill-rule="evenodd" d="M36 29v12zm-6 6h12z" clip-rule="evenodd" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                            d="M36 29v12m-6-6h12m-15-7h-8.2c-4.48 0-6.72 0-8.432.872a8 8 0 0 0-3.496 3.496C6 34.08 6 36.32 6 40.8V42h21" />
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
                    <path
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600"
                        viewBox="0 0 24 24"><!-- Icon from Huge Icons by Hugeicons - undefined -->
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5" color="currentColor">
                            <path d="M20.5 13.5a8.5 8.5 0 1 1-17 0a8.5 8.5 0 0 1 17 0" />
                            <path d="M12 19a5.5 5.5 0 1 1 0-11m1.5-4.5a1.5 1.5 0 1 0-3 0a1.5 1.5 0 0 0 3 0m-1.5 10L15 9" />
                        </g>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-yellow-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                    viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                    <path fill="currentColor"
                        d="M4 19v-2h2v-7q0-2.075 1.25-3.687T10.5 4.2V2h3v2.2q2 .5 3.25 2.113T18 10v7h2v2zm8 3q-.825 0-1.412-.587T10 20h4q0 .825-.587 1.413T12 22m-4-5h8v-7q0-1.65-1.175-2.825T12 6T9.175 7.175T8 10zm-6-7q0-2.5 1.113-4.587T6.1 1.95l1.175 1.6q-1.5 1.1-2.387 2.775T4 10zm18 0q0-2-.888-3.675T16.726 3.55l1.175-1.6q1.875 1.375 2.988 3.463T22 10z" />
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600"
                        viewBox="0 0 16 16"><!-- Icon from Gitlab SVGs by GitLab B.V. - https://gitlab.com/gitlab-org/gitlab-svgs/-/blob/main/LICENSE -->
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M8.175.002a8 8 0 1 0 2.309 15.603a.75.75 0 0 0-.466-1.426a6.5 6.5 0 1 1 3.996-8.646a.75.75 0 0 0 1.388-.569A8 8 0 0 0 8.175.002M8.75 3.75a.75.75 0 0 0-1.5 0v3.94L5.216 9.723a.75.75 0 1 0 1.06 1.06L8.53 8.53l.22-.22zM15 15a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-.25-6.25a.75.75 0 0 0-1.5 0v3.5a.75.75 0 0 0 1.5 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                    viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                    <path fill="currentColor"
                        d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m-1-4h2V7h-2zm1 10.3L8.65 20H4v-4.65L.7 12L4 8.65V4h4.65L12 .7L15.35 4H20v4.65L23.3 12L20 15.35V20h-4.65zm0-2.8l2.5-2.5H18v-3.5l2.5-2.5L18 9.5V6h-3.5L12 3.5L9.5 6H6v3.5L3.5 12L6 14.5V18h3.5zm0-8.5" />
                </svg>
                <span class="font-medium">Perlu perpanjangan</span>
            </div>
        </div>
    </div>

    <!-- Activity Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 hidden">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-500"
                    viewBox="0 0 24 24"><!-- Icon from Google Material Icons by Material Design Authors - https://github.com/material-icons/material-icons/blob/master/LICENSE -->
                    <path fill="currentColor"
                        d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z" />
                </svg>
            </div>
            {{-- <p class="text-4xl font-bold text-purple-600 mb-2">{{ number_format($recentSessions->count()) }}</p> --}}
            <p class="text-4xl font-bold text-purple-600 mb-2">{{ 'ganti nanti' }}</p>

            <p class="text-sm text-gray-600">Session dalam 24 jam terakhir</p>
        </div>
    </div>

    <!-- Enhanced Growth Chart -->
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                📈 Pertumbuhan Pelanggan
                <span class="text-sm font-normal text-gray-500">(30 Hari Terakhir)</span>
            </h3>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                <span>Pelanggan Baru</span>
            </div>
        </div>
        <div class="relative">
            <canvas id="growthChart" height="80"></canvas>
        </div>
    </div>

    <!-- Top Packages with Better Design -->
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            🏆 Paket Terpopuler
        </h3>
        @if ($topPackages->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-500 text-lg">Belum ada pelanggan terdaftar</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach ($topPackages as $index => $pkg)
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $pkg->name }}</h4>
                                <p class="text-sm text-gray-500">Paket bandwidth</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-indigo-600">{{ $pkg->customers_count }}</p>
                            <p class="text-sm text-gray-500">pelanggan</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <!-- Di bagian atas tab -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <h3 class="text-sm font-medium text-yellow-800 mb-2">⏰ Penanganan Pelanggan Kedaluwarsa</h3>
        <p class="text-sm text-yellow-700 mb-3">
            Jalankan proses pengecekan pelanggan yang akan atau sudah kedaluwarsa.
            Termasuk nonaktifkan, sinkronisasi, dan kirim notifikasi.
        </p>
        <div x-data="{ mikrotikId: '{{ $mikrotik->id }}' }">
            <div x-data="{ mikrotikId: '{{ $mikrotik->id }}' }">
                <button @click="$dispatch('run-expire-command', { mikrotikId: mikrotikId })"
                    class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    🔄 Jalankan Penanganan
                </button>
            </div>
        </div>
    </div>
    <!-- Enhanced Tabs -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <nav class="flex space-x-0 border-b border-gray-200 bg-gray-50 overflow-x-auto">
            <a href="#customers"
                class="tab-link px-8 py-4 text-sm font-semibold text-gray-700 hover:text-indigo-600 ">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden md:block"
                        viewBox="0 0 26 26"><!-- Icon from Garden SVG Icons by Zendesk - https://github.com/zendeskgarden/svg-icons/blob/main/LICENSE.md -->
                        <path fill="currentColor"
                            d="M22.136 10h-5.282c-.472 0-.854-.443-.854-.989V8.99c0-.546.382-.989.854-.989h5.282c.476 0 .864.45.864 1s-.388 1-.864 1zm0 4h-5.282c-.472 0-.854-.443-.854-.989v-.022c0-.546.382-.989.854-.989h5.282c.476 0 .864.45.864 1s-.388 1-.864 1m.022 4h-3.327c-.46 0-.831-.443-.831-.989v-.022c0-.546.372-.989.831-.989h3.327c.463 0 .842.45.842 1s-.379 1-.842 1M10 13a4 4 0 1 1 0-8a4 4 0 0 1 0 8m-6.03 8.073c-.583 0-1.048-.518-.96-1.093A7.07 7.07 0 0 1 10 14a7.07 7.07 0 0 1 6.989 5.98c.09.575-.376 1.093-.958 1.093z" />
                    </svg>
                    Customers
                </div>
            </a>
            <a href="#online-users" class="tab-link px-8 py-4 text-sm font-semibold text-gray-700 hover:text-indigo-600">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                        viewBox="0 0 32 32"><!-- Icon from Carbon by IBM - undefined -->
                        <circle cx="26" cy="16" r="4" fill="currentColor" />
                        <path fill="currentColor"
                            d="M22 30h-2v-5a5 5 0 0 0-5-5H9a5 5 0 0 0-5 5v5H2v-5a7 7 0 0 1 7-7h6a7 7 0 0 1 7 7zM12 4a5 5 0 1 1-5 5a5 5 0 0 1 5-5m0-2a7 7 0 1 0 7 7a7 7 0 0 0-7-7" />
                    </svg>
                    Online Users
                </div>
            </a>
            <a href="#packages" class="tab-link px-8 py-4 text-sm font-semibold text-gray-700 hover:text-indigo-600">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden md:block"
                        viewBox="0 0 24 24"><!-- Icon from Material Symbols Light by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                        <path fill="currentColor"
                            d="M10.758 15.192q.452.452 1.18.415q.73-.038 1.078-.515l3.841-5.359q.205-.296-.035-.536t-.537-.036l-5.377 3.824q-.497.334-.559 1.032t.409 1.175M5.1 19q-.277 0-.51-.141q-.234-.142-.394-.386q-.592-1.021-.894-2.158T3 14q0-1.868.709-3.51t1.924-2.857T8.49 5.709T12 5q1.857 0 3.487.698q1.63.697 2.852 1.9q1.223 1.204 1.936 2.818q.714 1.615.72 3.469q.005 1.244-.294 2.412q-.299 1.169-.903 2.253q-.14.244-.38.347q-.239.103-.524.103z" />
                    </svg>
                    Bandwidth Packages
                </div>
            </a>
            <a href="#ippool" class="tab-link px-8 py-4 text-sm font-semibold text-gray-700 hover:text-indigo-600">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden md:block"
                        viewBox="0 0 24 24"><!-- Icon from Iconoir by Luca Burgio - https://github.com/iconoir-icons/iconoir/blob/main/LICENSE -->
                        <g fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M2 15V9a6 6 0 0 1 6-6h8a6 6 0 0 1 6 6v6a6 6 0 0 1-6 6H8a6 6 0 0 1-6-6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6M9 9v6m3-3h2.5a1.5 1.5 0 0 0 1.5-1.5v0A1.5 1.5 0 0 0 14.5 9H12" />
                        </g>
                    </svg>
                    IP Pool
                </div>
            </a>
            <a href="#monitoring" class="tab-link px-8 py-4 text-sm font-semibold text-gray-700 hover:text-indigo-600">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden md:block"
                        viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                        <path fill="currentColor"
                            d="m15.45 15.05l1.1-1.05l-2.1-2.1q.275-.425.413-.9T15 10q0-1.475-1.037-2.488T11.5 6.5T9.037 7.513T8 10t1.038 2.488T11.5 13.5q.525 0 .988-.137t.912-.413zM11.5 12q-.825 0-1.412-.587T9.5 10t.588-1.412T11.5 8q.8 0 1.4.588T13.5 10t-.587 1.413T11.5 12M4 18q-.825 0-1.412-.587T2 16V5q0-.825.588-1.412T4 3h16q.825 0 1.413.588T22 5v11q0 .825-.587 1.413T20 18zm-3 3v-2h22v2z" />
                    </svg>
                    Monitoring
                </div>
            </a>
            <a href="#logs" class="tab-link px-8 py-4 text-sm font-semibold text-gray-700 hover:text-indigo-600">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden md:block"
                        viewBox="0 0 24 24"><!-- Icon from Tabler Icons by Paweł Kuna - https://github.com/tabler/tabler-icons/blob/master/LICENSE -->
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M4 12h.01M4 6h.01M4 18h.01M8 18h2m-2-6h2M8 6h2m4 0h6m-6 6h6m-6 6h6" />
                    </svg>
                    Logs
                </div>
            </a>
        </nav>

        <!-- Tab Content with Better Spacing -->
        <div class="p-8">
            <div id="customers" class="tab-content hidden">
                @include('tenant.mikrotik.tabs.customers')
            </div>
            <div id="online-users" class="tab-content hidden">
                @include('tenant.mikrotik.tabs.online-users')
            </div>
            <div id="packages" class="tab-content hidden">
                @include('tenant.mikrotik.tabs.packages')
            </div>
            <div id="ippool" class="tab-content hidden">
                @include('tenant.mikrotik.tabs.ippool')
            </div>
            <div id="monitoring" class="tab-content hidden">
                {{-- @include('tenant.mikrotik.tabs.monitoring') --}}
            </div>
            <div id="logs" class="tab-content hidden">
                {{-- <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Log feature coming soon</p>
                </div> --}}
                @include('tenant.mikrotik.tabs.logs')
            </div>
        </div>
    </div>

    <!-- Hidden Forms -->
    <form id="sync-customers-form" action="{{ route('tenant.mikrotik.sync-customers', $mikrotik) }}" method="POST"
        style="display: none;">
        @csrf
    </form>
    <form id="sync-ippools-form" action="{{ route('tenant.mikrotik.sync-ippools', $mikrotik) }}" method="POST"
        style="display: none;">
        @csrf
    </form>
    <x-expire-command-modal />
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Enhanced Chart Configuration
        const ctx = document.getElementById('growthChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.4)');
        gradient.addColorStop(1, 'rgba(102, 126, 234, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels), // Replace with actual PHP data
                datasets: [{
                    label: 'Jumlah Pelanggan Baru',
                    data: @json($data), // Replace with actual PHP data
                    borderColor: '#667eea',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#764ba2',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return 'Tanggal: ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Pelanggan Baru: ' + context.parsed.y + ' orang';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)',
                            borderDash: [2, 2]
                        },
                        ticks: {
                            stepSize: 1,
                            color: '#6b7280',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            callback: function(value) {
                                return value + ' orang';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        hoverBorderWidth: 3
                    }
                }
            }
        });

        // Enhanced Tab Navigation with URL Persistence
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('href').substring(1);

                // Update URL tanpa reload
                const url = new URL(window.location);
                url.searchParams.set('tab', target);
                window.history.pushState({}, '', url);

                // Show target tab
                showTab(target);

                // Update active class
                document.querySelectorAll('.tab-link').forEach(t => {
                    t.classList.remove('active', 'text-indigo-600');
                    t.classList.add('text-gray-700');
                });
                this.classList.add('active', 'text-indigo-600');
                this.classList.remove('text-gray-700');
            });
        });

        // Fungsi untuk tampilkan tab
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.opacity = '0';
                setTimeout(() => {
                    content.classList.add('hidden');
                }, 150);
            });

            setTimeout(() => {
                const targetContent = document.getElementById(tabName);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                    setTimeout(() => {
                        targetContent.style.opacity = '1';
                    }, 50);
                }
            }, 150);
        }

        // Saat halaman load, cek parameter tab
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const savedTab = urlParams.get('tab');

            // Default tab
            let activeTab = 'customers';

            if (savedTab && document.getElementById(savedTab)) {
                activeTab = savedTab;
            }

            // Tampilkan tab yang sesuai
            showTab(activeTab);

            // Tandai tab sebagai aktif
            const activeLink = document.querySelector(`.tab-link[href="#${activeTab}"]`);
            if (activeLink) {
                activeLink.classList.add('active', 'text-indigo-600');
                activeLink.classList.remove('text-gray-700');
            }

            // ... (lanjutkan inisialisasi lainnya)
        });
        // Enhanced API Functions with Loading States
        function testConnection(id) {
            const button = event.target;
            const originalText = button.innerHTML;

            // Show loading state
            button.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Testing...
    `;
            button.disabled = true;

            // Replace with actual AJAX call
            fetch(`/api/mikrotik/${id}/test-connection`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    button.innerHTML = originalText;
                    button.disabled = false;

                    if (data.success) {
                        showNotification('Connection test successful!', 'success');
                    } else {
                        showNotification('Connection test failed!', 'error');
                    }
                })
                .catch(error => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    showNotification('Connection test failed!', 'error');
                });
        }

        function syncData(type) {
            const button = event.target;
            const originalText = button.innerHTML;

            // Show loading state
            button.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Syncing...
    `;
            button.disabled = true;

            // Submit the appropriate form
            if (type === 'customers') {
                document.getElementById('sync-customers-form').submit();
            } else if (type === 'ippools') {
                document.getElementById('sync-ippools-form').submit();
            }
        }

        // Notification System
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform translate-x-full transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
            notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
            </svg>
            <span class="font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 hover:bg-white/20 rounded p-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                </svg>
            </button>
        </div>
    `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Real-time Updates (Optional - you can implement WebSocket here)
        function startRealTimeUpdates() {
            // This would connect to your WebSocket or use polling
            setInterval(() => {
                updateDashboardStats();
            }, 30000); // Update every 30 seconds
        }

        // Function to update dashboard statistics via AJAX
        function updateDashboardStats() {
            fetch('/api/mikrotik/dashboard-stats', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update statistics cards
                    updateStatCard('totalCustomers', data.totalCustomers);
                    updateStatCard('activeCount', data.activeCount);
                    updateStatCard('expiringSoon', data.expiringSoon);
                    updateStatCard('expiredToday', data.expiredToday);
                    updateStatCard('onlineUsers', data.onlineUsersCount);
                    // updateStatCard('recentSessions', data.recentSessions);
                })
                .catch(error => {
                    console.error('Error updating dashboard stats:', error);
                });
        }

        // Helper function to update individual stat cards
        function updateStatCard(statType, newValue) {
            const statElement = document.querySelector(`[data-stat="${statType}"]`);
            if (statElement) {
                const currentValue = parseInt(statElement.textContent.replace(/,/g, ''));
                if (currentValue !== newValue) {
                    // Add animation for value change
                    statElement.style.transform = 'scale(1.1)';
                    statElement.style.transition = 'transform 0.3s ease';

                    setTimeout(() => {
                        statElement.textContent = newValue.toLocaleString();
                        statElement.style.transform = 'scale(1)';
                    }, 150);
                }
            }
        }

        // Initialize smooth scrolling for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add fade-in animation for tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.transition = 'opacity 0.3s ease-in-out';
            content.style.opacity = content.classList.contains('hidden') ? '0' : '1';
        });

        // Advanced Search Functionality (for tables)
        function initializeSearch() {
            const searchInputs = document.querySelectorAll('[data-search]');

            searchInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const targetTable = document.querySelector(this.dataset.search);
                    const rows = targetTable.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                            row.style.animation = 'fadeIn 0.3s ease-in-out';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        }

        // Data Export Functions
        function exportToCSV(tableId, filename) {
            const table = document.getElementById(tableId);
            const rows = Array.from(table.querySelectorAll('tr'));

            const csvContent = rows.map(row => {
                const cols = Array.from(row.querySelectorAll('td, th'));
                return cols.map(col => {
                    let data = col.textContent.trim();
                    // Escape quotes and wrap in quotes if contains comma
                    data = data.replace(/"/g, '""');
                    if (data.includes(',') || data.includes('"') || data.includes('\n')) {
                        data = `"${data}"`;
                    }
                    return data;
                }).join(',');
            }).join('\n');

            const blob = new Blob([csvContent], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', filename + '.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        // Print Functionality
        function printTable(tableId) {
            const table = document.getElementById(tableId);
            const printWindow = window.open('', '', 'height=600,width=800');

            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write(
                '<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>'
            );
            printWindow.document.write('</head><body>');
            printWindow.document.write(table.outerHTML);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial active tab
            const activeTab = document.querySelector('.tab-link.active');
            if (activeTab) {
                activeTab.classList.add('text-indigo-600');
                activeTab.classList.remove('text-gray-700');
            }

            // Add loading animation to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Initialize search functionality
            initializeSearch();

            // Start real-time updates
            startRealTimeUpdates();

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + R for refresh
                if (e.ctrlKey && e.key === 'r') {
                    e.preventDefault();
                    location.reload();
                }

                // Ctrl + S for sync
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    syncData('customers');
                }
            });
        });

        // // Error Handling
        // window.addEventListener('error', function(e) {
        //     console.error('JavaScript Error:', e.error);
        //     showNotification('An error occurred. Please refresh the page.', 'error');
        // });

        // // Network Status Monitoring
        // window.addEventListener('online', function() {
        //     showNotification('Connection restored', 'success');
        // });

        // window.addEventListener('offline', function() {
        //     showNotification('Connection lost. Some features may not work.', 'error');
        // });
    </script>
@endpush
