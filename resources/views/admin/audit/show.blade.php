@extends('layouts.admin')
@section('title', 'Detail Audit')
@section('page-title', 'Lihat Detail Aktivitas')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Detail Audit Log</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $audit->event === 'created' ? 'bg-green-100 text-green-800' : 
                       ($audit->event === 'updated' ? 'bg-blue-100 text-blue-800' : 
                       ($audit->event === 'deleted' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ ucfirst($audit->event) }}
                </span>
            </div>
            
            <!-- Informasi Umum - Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                <!-- User Info -->
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">User</p>
                        <p class="text-sm font-medium text-gray-900">{{ optional($audit->user)->name ?? 'System' }}</p>
                    </div>
                </div>

                <!-- Table Info -->
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tabel & ID</p>
                        <p class="text-sm font-medium text-gray-900">{{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</p>
                    </div>
                </div>

                <!-- IP Address -->
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">IP Address</p>
                        <p class="text-sm font-medium text-gray-900">{{ $audit->ip_address }}</p>
                    </div>
                </div>

                <!-- Timestamp -->
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                        <p class="text-sm font-medium text-gray-900">{{ $audit->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-400">{{ $audit->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Comparison -->
        <div class="grid grid-cols-1 {{ $audit->event !== 'created' ? 'lg:grid-cols-2' : '' }} gap-6">
            @if($audit->event !== 'created')
                <!-- Data Sebelum -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-red-50 border-b border-red-200 px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-red-800">Data Sebelum</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        @if(empty($audit->old_values))
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-4.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Tidak ada data sebelumnya</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($audit->old_values as $key => $value)
                                    <div class="flex">
                                        <span class="text-sm font-medium text-gray-600 w-1/3">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                        <span class="text-sm text-gray-800 w-2/3 break-words">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Data Sesudah -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-green-50 border-b border-green-200 px-6 py-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <h3 class="text-lg font-semibold text-green-800">
                            {{ $audit->event === 'created' ? 'Data Dibuat' : 'Data Sesudah' }}
                        </h3>
                    </div>
                </div>
                <div class="p-6">
                    @if(empty($audit->new_values))
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">Data telah dihapus</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($audit->new_values as $key => $value)
                                <div class="flex">
                                    <span class="text-sm font-medium text-gray-600 w-1/3">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="text-sm text-gray-800 w-2/3 break-words">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Raw Data (Collapsible) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <button type="button" class="flex items-center justify-between w-full text-left" onclick="toggleRawData()">
                    <h3 class="text-lg font-semibold text-gray-700">Raw Data (JSON)</h3>
                    <svg id="rawDataIcon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            <div id="rawDataContent" class="hidden">
                <div class="p-6 space-y-4">
                    @if($audit->event !== 'created' && !empty($audit->old_values))
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 mb-2">Data Sebelum (JSON):</h4>
                            <pre class="bg-gray-50 p-4 rounded-lg text-xs overflow-x-auto border"><code>{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                        </div>
                    @endif
                    
                    @if(!empty($audit->new_values))
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 mb-2">Data Sesudah (JSON):</h4>
                            <pre class="bg-gray-50 p-4 rounded-lg text-xs overflow-x-auto border"><code>{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.audit.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Kembali ke Daftar Audit
            </a>
        </div>
    </div>

    <script>
        function toggleRawData() {
            const content = document.getElementById('rawDataContent');
            const icon = document.getElementById('rawDataIcon');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>
@endsection