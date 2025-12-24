<!-- resources/views/technician.blade.php -->

@extends('layouts.nologin')
@section('title', 'Daftar Pelanggan Terdaftar - Teknisi')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-6 sm:py-12 px-3 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-indigo-600 px-4 sm:px-8 py-4 sm:py-6 text-white">
                    <h1 class="text-xl sm:text-2xl font-bold text-center">📋 Daftar Pelanggan Terdaftar</h1>
                    <p class="text-indigo-100 text-center mt-1 text-sm sm:text-base">Untuk pemasangan perangkat baru</p>
                </div>

                <div class="p-4 sm:p-8">
                    <!-- Form Input Mikrotik UUID -->
                    <form id="mikrotik-form" class="mb-6 sm:mb-8">
                        @csrf
                        @method('GET')
                        <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-gray-200">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">🔐 Masukkan Kode
                                MikroTik</h2>
                            <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                                Silakan masukkan Kode MikroTik Anda untuk melihat daftar pelanggan yang perlu dipasang.
                            </p>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="text" name="mikrotik_uuid" id="mikrotik_uuid"
                                    placeholder="Contoh: c9db0297-4403-4cc9-a888-6b765ad07537"
                                    class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-full"
                                    required>
                                <button type="submit"
                                    class="px-4 sm:px-6 py-2.5 sm:py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition text-sm sm:text-base whitespace-nowrap">
                                    Tampilkan
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Kode Mikrotik biasanya diberikan oleh admin sistem.
                            </p>
                        </div>
                    </form>

                    <!-- Loading Skeleton -->
                    <div id="loading" class="hidden">
                        <div class="loading-skeleton h-10 sm:h-12 rounded-lg mb-3 sm:mb-4"></div>
                        <div class="loading-skeleton h-10 sm:h-12 rounded-lg mb-3 sm:mb-4"></div>
                        <div class="loading-skeleton h-10 sm:h-12 rounded-lg"></div>
                    </div>

                    <!-- Customer List -->
                    <div id="customer-list" class="hidden">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-2">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-800">Pelanggan yang Perlu Dipasang</h2>
                            <span id="total-count"
                                class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs sm:text-sm font-medium self-start sm:self-auto">
                                {{ $customers->count() }} pelanggan
                            </span>
                        </div>
                        @dump($customers)
                        <div id="customers-container" class="space-y-3 sm:space-y-4">
                            @foreach ($customers as $customer)
                                <div class="flex flex-col sm:flex-row sm:justify-between gap-3 sm:gap-0">
                                    <div class="customer-info flex-1">
                                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">{{$customer.name}}</h3>
                                        <p class="text-xs sm:text-sm text-gray-600 mt-1">{{$customer.address}}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{$customer.package_name || 'Tanpa paket'}}</p>
                                    </div>
                                    <div class="text-left sm:text-right flex-shrink-0">
                                        <a class="text-xs sm:text-sm font-medium text-gray-800"
                                            href="https://wa.me/{{$customer.phone}}" target="_blank">{{$customer.phone}}</a>
                                    </div>
                                </div>
                  
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Username dan Password PPPOE
                                    </h3>
                                    <div class="flex w-full justify-between">
                                        <p class="text-xs sm:text-sm text-gray-700">{{$customer.username}}</p>
                                        <p class="text-xs p-2  bg-indigo-600 rounded-sm hover:cursor-pointer text-sm text-white"
                                            onclick="copyText('{{$customer.username}')">SALIN</p>
                                    </div>

                                </div>

                                <form id="mikrotik-form" class="mb-6 mt-5 sm:mb-8">
                                    <div class="flex gap-2">
                                        <input name="odc" required id="odc" placeholder="ODC"
                                            class="flex-1 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-2/3">
                                        <input name="odp" required id="odp" placeholder="ODP"
                                            class="flex-1 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-2/3">
                                        <input type="number" required name="port" id="port" placeholder="PORT"
                                            class="flex-1 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-1/3">
                                    </div>
                                    <input type="text" name="maps_url" id="maps_url"
                                        placeholder="URL MAPS LOKASI PEMASANGAN"
                                        class="flex-1 mt-2 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-full">
                                    <button class="w-full p-2 bg-indigo-600 text-white rounded-lg mt-2"
                                        type="submit">Selesai Instalasi dan Simpan</button>
                                </form>
                            @endforeach
                        </div>

                        <div class="mt-6 text-center">
                            <button onclick="document.getElementById('mikrotik-form').reset(); resetForm()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">
                                &larr; Ganti MikroTik
                            </button>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="empty-state" class="hidden text-center py-8 sm:py-12">
                        <svg class="w-12 sm:w-16 h-12 sm:h-16 text-gray-300 mx-auto mb-4" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-base sm:text-lg">Tidak ada pelanggan dengan status
                            <strong>"terdaftar"</strong></p>
                        <p class="text-gray-400 text-xs sm:text-sm">Semua pelanggan sudah diproses atau aktif.</p>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="hidden mt-4 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-700 text-xs sm:text-sm text-center" id="error-text"></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-500 text-xs sm:text-sm mt-4 sm:mt-6 px-4">
                &copy; {{ date('Y') }} xBilling. Hak cipta dilindungi.
            </p>
        </div>
    </div>

    <style>
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

        /* Mobile-specific improvements */
        @media (max-width: 640px) {
            .customer-card {
                padding: 12px !important;
            }

            .customer-info {
                gap: 8px;
            }

            /* Better text wrapping for long UUIDs on mobile */
            #mikrotik_uuid {
                font-size: 12px;
            }

            /* Improve touch targets */
            button,
            input {
                min-height: 44px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        function resetForm() {
            document.getElementById('customer-list').classList.add('hidden');
            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('error-message').classList.add('hidden');
        }

        function copyText(text) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    alert("Berhasil di-copy");
                })
                .catch(err => {
                    alert("Gagal copy, harap copy manual:", err);
                });
        }

        document.getElementById('mikrotik-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const uuid = document.getElementById('mikrotik_uuid').value.trim();
            const loading = document.getElementById('loading');
            const customerList = document.getElementById('customer-list');
            const emptyState = document.getElementById('empty-state');
            const errorMessage = document.getElementById('error-message');
            const customersContainer = document.getElementById('customers-container');
            const totalCount = document.getElementById('total-count');

            // Reset
            loading.classList.remove('hidden');
            customerList.classList.add('hidden');
            emptyState.classList.add('hidden');
            errorMessage.classList.add('hidden');
            customersContainer.innerHTML = '';

            try {
                const response = await fetch(`{{ route('technician.queue-lists.post') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        mikrotik_uuid: uuid
                    })
                });

                const data = await response.json();

                loading.classList.add('hidden');

                if (data.success) {
                    if (data.customers.length === 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        totalCount.textContent = `{{data.customers.length} pelanggan`;
                        data.customers.forEach(customer => {
                            const div = document.createElement('div');
                            div.className =
                                'customer-card bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200';
                            div.innerHTML = `
                                <div class="flex flex-col sm:flex-row sm:justify-between gap-3 sm:gap-0">
                                    <div class="customer-info flex-1">
                                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">{{$customer.name}}</h3>
                                        <p class="text-xs sm:text-sm text-gray-600 mt-1">{{$customer.address}}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{$customer.package_name || 'Tanpa paket'}}</p>
                                    </div>
                                    <div class="text-left sm:text-right flex-shrink-0">
                                        <a class="text-xs sm:text-sm font-medium text-gray-800" href="https://wa.me/{{$customer.phone}" target="_blank">{{$customer.phone || '-'}}</a>
                                    </div>
                                </div>
                                {{$customer.username ? `
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Username dan Password PPPOE</h3>
                                            <div class="flex w-full justify-between">
                                                <p class="text-xs sm:text-sm text-gray-700">{{$customer.username}}</p>
                                                <p class="text-xs p-2  bg-indigo-600 rounded-sm hover:cursor-pointer text-sm text-white" onclick="copyText('{{$customer.username}')">SALIN</p>
                                            </div>
                                            
                                        </div>
                                    
                                    <form id="mikrotik-form" class="mb-6 mt-5 sm:mb-8">
                                        <div class="flex gap-2">
                                            <input  name="odc" required id="odc" placeholder="ODC" class="flex-1 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-2/3">
                                            <input  name="odp" required id="odp" placeholder="ODP" class="flex-1 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-2/3">
                                            <input type="number" required name="port"  id="port" placeholder="PORT" class="flex-1 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-1/3">
                                        </div>
                                        <input type="text"  name="maps_url"  id="maps_url" placeholder="URL MAPS LOKASI PEMASANGAN" class="flex-1 mt-2 px-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-full">
                                        <button class="w-full p-2 bg-indigo-600 text-white rounded-lg mt-2" type="submit">Selesai Instalasi dan Simpan</button>
                                    </form>
                                    ` : ''}
                            `;
                            customersContainer.appendChild(div);
                        });
                        customerList.classList.remove('hidden');
                    }
                } else {
                    document.getElementById('error-text').textContent = data.message;
                    errorMessage.classList.remove('hidden');
                }
            } catch (err) {
                document.getElementById('error-text').textContent = 'Gagal terhubung ke server.';
                errorMessage.classList.remove('hidden');
                loading.classList.add('hidden');
            }
        });

        function formatStatus(status) {
            const map = {
                'terdaftar': 'Terdaftar',
                'aktif': 'Aktif',
                'isolir': 'Isolir'
            };
            return map[status] || status;
        }
    </script>
@endsection
