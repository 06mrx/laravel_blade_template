<!-- resources/views/tenant/billing-cycle/edit.blade.php -->

@extends('layouts.admin')
@section('title', 'Edit Skema Penagihan')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Skema Penagihan</h1>

        <form action="{{ route('tenant.billing_cycle.update', $billingCycle) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Skema</label>
                <input type="text" name="name" id="name" value="{{ old('name', $billingCycle->name) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Jenis -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Penagihan</label>
                <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg" onchange="toggleDueDays()">
                    <option value="fixed" {{ $billingCycle->type === 'fixed' ? 'selected' : '' }}>Fixed (Tanggal Tetap)</option>
                    <option value="segmented" {{ $billingCycle->type === 'segmented' ? 'selected' : '' }}>Segmented (Banyak Tanggal)</option>
                    <option value="anniversary" {{ $billingCycle->type === 'anniversary' ? 'selected' : '' }}>Anniversary (Tanggal Daftar)</option>
                </select>
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div class="mb-6" id="due-days-field" style="{{ $billingCycle->type === 'anniversary' ? 'display: none' : '' }}">
                <label for="due_days" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Jatuh Tempo</label>
                <input type="text" name="due_days" id="due_days" value="{{ old('due_days', implode(',', $billingCycle->due_days)) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                       placeholder="Contoh: 5,15,25">
            </div>

            <!-- MikroTik -->
            <div class="mb-6">
                <label for="mikrotik_id" class="block text-sm font-medium text-gray-700 mb-2">MikroTik (Opsional)</label>
                <select name="mikrotik_id" id="mikrotik_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">- Semua MikroTik -</option>
                    @foreach(auth()->user()->mikrotiks as $mikrotik)
                        <option value="{{ $mikrotik->id }}" {{ $billingCycle->mikrotik_id == $mikrotik->id ? 'selected' : '' }}>
                            {{ $mikrotik->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Default -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_default" value="1" {{ $billingCycle->is_default ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Jadikan sebagai skema default</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('tenant.billing_cycle.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Perbarui
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleDueDays() {
            const type = document.getElementById('type').value;
            const field = document.getElementById('due-days-field');
            field.style.display = type === 'anniversary' ? 'none' : 'block';
        }
        toggleDueDays();
    </script>
@endsection