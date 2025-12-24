@extends('layouts.admin')
@section('title', 'Buat Akun Bank')
@section('page-title', 'Buat Akun Bank Baru')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('tenant.bank_account.update', $bankAccount) }}" method="POST" >
            @csrf
            @method('PUT')
            <!-- Bank Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $bankAccount->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: BRI" required>
            </div>
            <!-- owner Name -->
            <div class="mb-6">
                <label for="owner" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik</label>
                <input type="text" name="owner" id="owner"
                       value="{{ old('owner', $bankAccount->owner) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: John Doe" required>
            </div>
            <!-- Account number -->
            <div class="mb-6">
                <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                <input type="text" name="account_number" id="account_number"
                       value="{{ old('account_number', $bankAccount->account_number) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: 123456789" required>
            </div>
            {{-- Status toggle checkbox is_active--}}
            <div class="mb-6">
                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <input type="checkbox" name="is_active" id="is_active" value="{{ old('is_active', $bankAccount->is_active) }}" {{ old('is_active', $bankAccount->is_active) == 1 ? 'checked' : '' }}> Aktif
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('tenant.bank_account.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Permission
                </button>
            </div>
        </form>
    </div>
@endsection