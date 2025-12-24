@extends('layouts.nologin')
@section('title', 'Daftar Pelanggan Terdaftar - Teknisi')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-sky-50 to-blue-50 py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-sky-500 to-blue-500 px-8 py-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Hasil Pencarian Tagihan</h1>
                            <div class="flex items-center text-sky-100">
                                <i class="fas fa-search mr-2"></i>
                                <span>Pencarian berhasil dilakukan</span>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-file-invoice-dollar text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    @if ($invoices->isEmpty())
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-search text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak Ada Tagihan Ditemukan</h3>
                            <p class="text-lg text-gray-500 mb-1">Maaf, kami tidak dapat menemukan tagihan yang Anda cari.
                            </p>
                            <p class="text-sm text-gray-400 mb-8">Pastikan nomor invoice atau username yang dimasukkan sudah
                                benar.</p>
                            <a href="{{ route('/') }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-500 text-white rounded-xl font-semibold hover:from-sky-600 hover:to-blue-600 transition-all transform hover:scale-105">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Coba Lagi
                            </a>
                        </div>
                    @else
                        <!-- Results Header -->
                        <div class="mb-8">
                            <div class="bg-gradient-to-r from-sky-50 to-blue-50 rounded-2xl p-6 border border-sky-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-800 mb-1">
                                            {{ $invoices->first()->customer->name }}
                                        </h2>
                                        <p class="text-gray-600">
                                            <i class="fas fa-file-invoice mr-2 text-sky-500"></i>
                                            <strong>{{ $invoices->count() }}</strong> tagihan ditemukan
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Total Outstanding</div>
                                        <div class="text-2xl font-bold text-red-600">
                                            Rp{{ number_format($invoices->where('status', '!=', 'paid')->sum('amount'), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @dd($invoices->first()->customer->user->bankAccounts()->count()) --}}
                        <!-- Bank Accounts Card -->
                        @if ($invoices->first()->customer->user->bankAccounts()->count() > 0)
                            <div class="mb-8">
                                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-4">
                                        <h3 class="text-lg font-bold text-white flex items-center">
                                            <i class="fas fa-university mr-3"></i>
                                            Informasi Rekening untuk Pembayaran
                                        </h3>
                                    </div>

                                    <div class="p-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach ($invoices->first()->customer->user->bankAccounts as $bankAccount)
                                                <div
                                                    class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-2 border border-gray-200 hover:shadow-md transition-all">
                                                    <div class="flex flex-col items-center  justify-center">
                                                        {{-- <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-university text-white"></i>
                                                        </div> --}}

                                                        <div class=" text-gray-800 text-center"><span
                                                                class="font-bold">{{ $bankAccount->name }}</span> <span
                                                                class="text-gray-500 text-xs">({{ $bankAccount->owner }})</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $bankAccount->account_number }}</div>

                                                    </div>
                                                    <button onclick="copyToClipboard('{{ $bankAccount->account_number }}')"
                                                        class="mt-3 w-full px-4 py-2 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition-all">
                                                        <i class="fas fa-copy mr-2"></i>
                                                        Salin
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                            <div class="flex items-start">
                                                <i class="fas fa-info-circle text-yellow-600 mr-3 mt-1"></i>
                                                <div class="text-sm text-yellow-800">
                                                    <strong>Petunjuk Pembayaran:</strong><br>
                                                    Transfer ke salah satu rekening di atas, lalu konfirmasi pembayaran
                                                    dengan menyertakan nomor invoice sebagai berita transfer.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Invoice Cards -->
                        <div class="space-y-4">
                            @foreach ($invoices as $invoice)
                                <div
                                    class="bg-white rounded-2xl shadow-md border-l-4 
                                    {{ $invoice->status === 'paid'
                                        ? 'border-green-400 hover:shadow-green-100'
                                        : ($invoice->status === 'overdue'
                                            ? 'border-red-400 hover:shadow-red-100'
                                            : 'border-yellow-400 hover:shadow-yellow-100') }} 
                                    hover:shadow-lg transition-all duration-300 group">

                                    <div class="p-6">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                            <!-- Left Section -->
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-4">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="w-12 h-12 rounded-xl bg-gradient-to-r from-sky-500 to-blue-500 flex items-center justify-center mr-4">
                                                            <i class="fas fa-file-invoice text-white text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h3
                                                                class="text-lg font-bold text-gray-800 group-hover:text-sky-600 transition-colors">
                                                                {{ $invoice->invoice_number }}
                                                            </h3>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $invoice->created_at->format('d M Y') }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Status Badge -->
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        {{ $invoice->status === 'paid'
                                                            ? 'bg-green-100 text-green-800 border border-green-200'
                                                            : ($invoice->status === 'overdue'
                                                                ? 'bg-red-100 text-red-800 border border-red-200'
                                                                : 'bg-yellow-100 text-yellow-800 border border-yellow-200') }}">
                                                        <i
                                                            class="fas {{ $invoice->status === 'paid' ? 'fa-check-circle' : ($invoice->status === 'overdue' ? 'fa-exclamation-triangle' : 'fa-clock') }} mr-1"></i>
                                                        {{ $invoice->status === 'paid' ? 'Lunas' : ($invoice->status === 'overdue' ? 'Terlambat' : 'Pending') }}
                                                    </span>
                                                </div>

                                                <!-- Invoice Details Grid -->
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="bg-gray-50 rounded-xl p-4">
                                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                                                            Paket Layanan</div>
                                                        <div class="font-semibold text-gray-800">
                                                            {{ $invoice->package?->name ?? 'Tidak Specified' }}
                                                        </div>
                                                    </div>

                                                    <div class="bg-gray-50 rounded-xl p-4">
                                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                                                            Jatuh Tempo</div>
                                                        <div
                                                            class="font-semibold {{ $invoice->status === 'overdue' ? 'text-red-600' : 'text-gray-800' }}">
                                                            {{ $invoice->due_date->format('d M Y') }}
                                                            @if ($invoice->status === 'overdue')
                                                                <span class="text-xs text-red-500 block">
                                                                    ({{ $invoice->due_date->diffForHumans() }})
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="bg-gradient-to-r from-sky-50 to-blue-50 rounded-xl p-4 border border-sky-200">
                                                        <div class="text-xs text-sky-700 uppercase tracking-wide mb-1">
                                                            Jumlah Tagihan</div>
                                                        <div class="font-bold text-xl text-sky-600">
                                                            Rp{{ number_format($invoice->amount, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Notes (if available) -->
                                                @if ($invoice->notes)
                                                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                                                        <div class="flex items-start">
                                                            <i class="fas fa-sticky-note text-blue-500 mr-3 mt-1"></i>
                                                            <div>
                                                                <div
                                                                    class="text-xs text-blue-700 uppercase tracking-wide mb-1">
                                                                    Catatan</div>
                                                                <p class="text-sm text-blue-800">{{ $invoice->notes }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Right Section - Actions -->
                                            {{-- <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col space-y-3">
                                                @if ($invoice->status !== 'paid')
                                                    <button
                                                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-medium hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 shadow-lg">
                                                        <i class="fas fa-credit-card mr-2"></i>
                                                        Bayar Sekarang
                                                    </button>
                                                @endif

                                                <button
                                                    class="px-6 py-3 bg-white border-2 border-sky-500 text-sky-600 rounded-xl font-medium hover:bg-sky-500 hover:text-white transition-all">
                                                    <i class="fas fa-download mr-2"></i>
                                                    Download PDF
                                                </button>

                                                <button
                                                    class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200 transition-all">
                                                    <i class="fas fa-eye mr-2"></i>
                                                    Detail
                                                </button>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Summary Card -->
                        <div class="mt-8 bg-gradient-to-r from-sky-500 to-blue-500 rounded-2xl p-6 text-white">
                            <h3 class="text-lg font-bold mb-4 flex items-center">
                                <i class="fas fa-chart-pie mr-3"></i>
                                Ringkasan Tagihan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $invoices->where('status', 'paid')->count() }}</div>
                                    <div class="text-sky-100 text-sm">Tagihan Lunas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $invoices->where('status', 'unpaid')->count() }}
                                    </div>
                                    <div class="text-sky-100 text-sm">Belum Dibayar</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $invoices->where('status', 'overdue')->count() }}
                                    </div>
                                    <div class="text-sky-100 text-sm">Terlambat</div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="mt-8 text-center">
                            <a href="{{ route('/') }}"
                                class="inline-flex items-center px-8 py-4 bg-white border-2 border-sky-500 text-sky-600 rounded-2xl font-semibold hover:bg-sky-500 hover:text-white transition-all transform hover:scale-105 shadow-lg">
                                <i class="fas fa-arrow-left mr-3"></i>
                                Kembali & Cari Lagi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            // alert(1)
            // navigator.clipboard.
            navigator.clipboard.writeText(text).then(function() {
                // Show success notification
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                notification.innerHTML = '<i class="fas fa-check mr-2"></i>Nomor rekening berhasil disalin!';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        .invoice-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .invoice-card:hover {
            transform: translateY(-2px);
        }

        .status-indicator {
            position: relative;
            overflow: hidden;
        }

        .status-indicator::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .invoice-card:hover .status-indicator::before {
            left: 100%;
        }

        .action-button {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .action-button:hover {
            transform: translateY(-1px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
@endsection
