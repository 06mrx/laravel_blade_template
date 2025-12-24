@extends('layouts.admin')
@section('title', 'Add New MikroTik')
@section('page-title', 'Add New MikroTik Device')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('tenant.mikrotik.store') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Device Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Kantor Pusat" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- IP Address -->
            <div class="mb-6">
                <label for="ip_address" class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. 192.168.1.1" required>
                @error('ip_address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Port -->
            <div class="mb-6">
                <label for="port" class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <input type="number" name="port" id="port" value="{{ old('port', 8728) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. 8728">
                @error('port')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Username -->
            <div class="mb-6">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. admin" required>
                @error('username')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Minimum 6 characters" required>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Notifikasi Email via Gmail -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-sm font-medium text-blue-800 mb-2">📧 Notifikasi Email (Opsional)</h3>
                <p class="text-xs text-blue-700 mb-3">
                    Isi data Gmail untuk mengirim notifikasi pelanggan (expired, reminder).
                    Harus aktifkan <strong>2-Step Verification</strong> dan buat <strong>App Password</strong>.
                </p>

                <div class="space-y-4">
                    <div>
                        <label for="gmail" class="block text-xs font-medium text-gray-700 mb-1">Email Gmail</label>
                        <input type="email" name="gmail" id="gmail" value="{{ old('gmail') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            placeholder="admin@tokonet.id">
                        <p class="text-xs text-gray-500 mt-1">Gunakan email yang aktif 2FA.</p>
                    </div>

                    <div>
                        <label for="app_password" class="block text-xs font-medium text-gray-700 mb-1">App Password</label>
                        <input type="password" name="app_password" id="app_password" value="{{ old('app_password') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            placeholder="16-digit app password">
                        <p class="text-xs text-gray-500 mt-1">
                            <a href="{{ route('tenant.mikrotik.tutorial') }}" target="_blank"
                                class="text-blue-600 hover:underline">
                                📚 Cara aktifkan 2FA & buat App Password
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. MikroTik di lokasi Bandung">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('tenant.mikrotik.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save MikroTik
                </button>
            </div>
        </form>
    </div>
@endsection
