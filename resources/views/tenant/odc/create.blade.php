@extends('layouts.admin')
@section('title', 'Buat ODC')
@section('page-title', 'Buat ODC Baru')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('tenant.odc.store') }}" method="POST">
            @csrf

            <!-- Bank Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Contoh: Server" required>
            </div>

            <div class="mb-6">
                <label for="mikrotik_id" class="block text-sm font-medium text-gray-700 mb-2">Perangkat Mikrotik</label>
                <select id="mikrotik_id" name="mikrotik_id" id="mikrotik_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    <option value="">- Pilih Perangkat Mikrotik -</option>
                    @foreach ($mikrotiks as $mikrotik)
                        <option value="{{ $mikrotik->id }}">{{ $mikrotik->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('tenant.odc.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save
                </button>
            </div>
        </form>
    </div>
@endsection
