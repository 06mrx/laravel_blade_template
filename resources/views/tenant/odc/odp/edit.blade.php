@extends('layouts.admin')
@section('title', 'Buat ODP')
@section('page-title', 'Buat ODP Baru')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('tenant.odc.odp.update', [$Odc, $Odp]) }}" method="POST" >
            @csrf
            @method('PUT')
            <!-- Bank Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $Odp->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: BRI" required>
            </div>
          
            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('tenant.odc.odp.index', [$Odc, $Odp]) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
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