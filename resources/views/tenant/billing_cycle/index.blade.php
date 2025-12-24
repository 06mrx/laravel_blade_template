@extends('layouts.admin')
@section('title', 'Skema Penagihan')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Skema Penagihan</h1>
            <a href="{{ route('tenant.billing_cycle.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                + Tambah Skema
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">MikroTik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Default</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($billingCycles as $cycle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium">{{ $cycle->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ ucfirst($cycle->type) }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($cycle->type === 'anniversary')
                                    -
                                @else
                                    {{ implode(', ', $cycle->due_days) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $cycle->mikrotik?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($cycle->is_default)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Ya</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('tenant.billing_cycle.edit', $cycle) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('tenant.billing_cycle.destroy', $cycle) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $billingCycles->links() }}
    </div>
@endsection