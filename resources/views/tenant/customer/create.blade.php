@extends('layouts.admin')
@section('title', 'Add Customer')
@section('page-title', 'Create New Customer')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        {{-- @dump($errors)         --}}
        <!-- Pemberitahuan Username & Password -->
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Info:</strong> Username dan password akan <u>otomatis</u> tergenerate
                dengan pola <strong> <code>IP_NIK</code> </strong>, Titik pada IP di hilangkan.<br>
                Anda dapat melihat username dan password tersebut di daftar user setelah customer berhasil dibuat. <br>
                <em>Contoh : 1921681100_1234567890123456</em>
            </p>
        </div>

        <form action="{{ route('tenant.customer.store') }}" method="POST">
            @csrf

            <!-- Hidden: from_mikrotik -->
            @if (request('from_mikrotik'))
                <input type="hidden" name="from_mikrotik" value="{{ request('from_mikrotik') }}">
                <input type="hidden" name="mikrotik_id" value="{{ request('from_mikrotik') }}">
            @endif

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. John Doe" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            {{-- KTP --}}
            <div class="mb-6">
                <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor KTP</label>
                <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. 7302010922990001" required>
                @error('id_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. john@example.com">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No Whatsapp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. +628123456789">
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" id="address" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Jl. Merdeka No. 1, Jakarta">{{ old('address') }}</textarea>
                @error('address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Username -->
            {{-- <div class="mb-6">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username"
                       value="{{ old('username') }}"
                       disabled
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. johndoe123" required>
                @error('username')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password"
                       value="{{ old('password') }}"
                       disabled
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Minimum 6 characters" required>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}

            <!-- Package -->
            <div class="mb-6">
                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Paket</label>
                <select name="package_id" id="package_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- No Package --</option>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} ({{ $package->speed_down }}/↓ {{ $package->speed_up }}/↑)
                        </option>
                    @endforeach
                </select>
                @error('package_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- IP Pool -->
            <div class="mb-6">
                <label for="ip_pool_id" class="block text-sm font-medium text-gray-700 mb-1">IP Pool</label>
                <select name="ip_pool_id" id="ip_pool_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- No Pool --</option>
                    @foreach ($ipPools as $pool)
                        <option value="{{ $pool->id }}" {{ old('ip_pool_id') == $pool->id ? 'selected' : '' }}>
                            {{ $pool->name }} ({{ $pool->range }})
                        </option>
                    @endforeach
                </select>
                @error('ip_pool_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Expired At -->
            <div class="mb-6">
                <label for="expired_at" class="block text-sm font-medium text-gray-700 mb-1">Tgl. Kadaluarsa
                    (Optional)</label>
                <input type="datetime-local" name="expired_at" id="expired_at" value="{{ old('expired_at') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin expired.</p>
                @error('expired_at')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ request('from_mikrotik') ? route('tenant.mikrotik.show', request('from_mikrotik')) : route('tenant.customer.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Customer
                </button>
            </div>
        </form>
    </div>
@endsection
