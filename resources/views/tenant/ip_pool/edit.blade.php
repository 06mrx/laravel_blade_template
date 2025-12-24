@extends('layouts.admin')
@section('title', 'Edit IP Pool')
@section('page-title', 'Edit IP Pool')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('tenant.ip_pool.update', $ipPool) }}" method="POST">
            @csrf
            @method('PUT')

            @if(request('from_mikrotik'))
                <input type="hidden" name="from_mikrotik" value="{{ request('from_mikrotik') }}">
                <input type="hidden" name="mikrotik_id" value="{{ request('from_mikrotik') }}">
            @endif

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Pool Name</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $ipPool->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. pppoe-pool" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Range -->
            <div class="mb-6">
                <label for="range" class="block text-sm font-medium text-gray-700 mb-1">IP Range</label>
                <input type="text" name="range" id="range"
                       value="{{ old('range', $ipPool->range) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 192.168.10.10-192.168.10.200" required>
                <p class="text-xs text-gray-500 mt-1">Format: start-end, misalnya 192.168.1.10-192.168.1.100</p>
                @error('range')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Next Pool -->
            <div class="mb-6">
                <label for="next_pool" class="block text-sm font-medium text-gray-700 mb-1">Next Pool (Optional)</label>
                <input type="text" name="next_pool" id="next_pool"
                       value="{{ old('next_pool', $ipPool->next_pool) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. backup-pool">
                @error('next_pool')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="e.g. IP Pool untuk pelanggan PPPoE">{{ old('description', $ipPool->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ request('from_mikrotik') ? route('tenant.mikrotik.show', request('from_mikrotik')) : route('tenant.ip_pool.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update IP Pool
                </button>
            </div>
        </form>
    </div>
@endsection