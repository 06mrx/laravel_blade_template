@extends('layouts.admin')
@section('title', 'Add New Package')
@section('page-title', 'Create Bandwidth Package')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('tenant.package.store') }}" method="POST">
            @csrf

            <!-- Hidden: from_mikrotik -->
            @if(request('from_mikrotik'))
                <input type="hidden" name="from_mikrotik" value="{{ request('from_mikrotik') }}">
                <input type="hidden" name="mikrotik_id" value="{{ request('from_mikrotik') }}">
            @endif

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Package Name</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 10Mbps 30 Hari" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Connection Type</label>
                <select name="type" id="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pppoe" {{ old('type') == 'pppoe' ? 'selected' : '' }}>PPPoE</option>
                    <option value="hotspot" {{ old('type') == 'hotspot' ? 'selected' : '' }}>Hotspot</option>
                </select>
                @error('type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Speed Down -->
            <div class="mb-6">
                <label for="speed_down" class="block text-sm font-medium text-gray-700 mb-1">Download Speed (k, M)</label>
                <input type="text" name="speed_down" id="speed_down"
                       value="{{ old('speed_down') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 10M" required>
                <p class="text-xs text-gray-500 mt-1">Contoh: 2M, 512k, 1024k</p>
                @error('speed_down')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Speed Up -->
            <div class="mb-6">
                <label for="speed_up" class="block text-sm font-medium text-gray-700 mb-1">Upload Speed (k, M)</label>
                <input type="text" name="speed_up" id="speed_up"
                       value="{{ old('speed_up') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 1M" required>
                <p class="text-xs text-gray-500 mt-1">Contoh: 1M, 256k</p>
                @error('speed_up')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Duration Days -->
            <div class="mb-6">
                <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-1">Duration (Days)</label>
                <input type="number" name="duration_days" id="duration_days"
                       value="{{ old('duration_days') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 30" min="1">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika unlimited.</p>
                @error('duration_days')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Quota (GB) -->
            <div class="mb-6">
                <label for="quota" class="block text-sm font-medium text-gray-700 mb-1">Data Quota (GB)</label>
                <input type="number" name="quota" id="quota"
                       value="{{ old('quota') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 10" min="1">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika unlimited.</p>
                @error('quota')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Price -->
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (IDR)</label>
                <input type="number" name="price" id="price"
                       value="{{ old('price') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 50000">
                @error('price')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="e.g. Paket premium untuk pelanggan prioritas">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ request('from_mikrotik') ? route('tenant.mikrotik.show', request('from_mikrotik')) : route('tenant.package.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Package
                </button>
            </div>
        </form>
    </div>
@endsection