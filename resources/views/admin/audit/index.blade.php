@extends('layouts.admin')
@section('title', 'Audit Logs')
@section('page-title', 'Kelola Log Aktivitas')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Log Aktivitas Sistem</h2>
        <div class="flex space-x-4 items-center">

            <!-- Form Pencarian -->
            <form action="{{ route('admin.audit.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari event, pengguna, tabel..."
                       class="px-4 py-2 rounded-lg text-xs border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @if(request('search'))
                    <button type="button"
                            onclick="window.location='{{ route('admin.audit.index') }}'"
                            class="absolute right-8 top-2 text-gray-400 hover:text-gray-600">&times;</button>
                @endif
                <button type="submit"
                        class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            <!-- Dropdown Show Per Page -->
            <form action="{{ route('admin.audit.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="per_page" class="text-xs text-gray-600">Tampilkan:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="text-xs rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ([10, 20, 50, 100] as $val)
                        <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tabel</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            @forelse ($audits as $audit)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ optional($audit->user)->name ?? 'System' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                        <span class="
                            @switch($audit->event)
                                @case('created') px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 @break
                                @case('updated') px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 @break
                                @case('deleted') px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 @break
                                @default px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800
                            @endswitch">
                            {{ $audit->event }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ class_basename($audit->auditable_type) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $audit->ip_address }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($audit->created_at)->format('d M Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 space-x-2">
                        <a href="{{ route('admin.audit.show', $audit->id) }}"
                           class="text-blue-600 hover:text-blue-900">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada log ditemukan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-3">
            {{ $audits->links() }}
        </div>
    </div>
@endsection