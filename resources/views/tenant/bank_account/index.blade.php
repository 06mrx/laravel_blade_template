@extends('layouts.admin')
@section('title', 'Akun Bank')
@section('page-title', 'Kelola Akun Bank')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Akun Bank</h2>
        <div class="flex space-x-4 items-center">
            <!-- Form Pencarian -->
            <form action="{{ route('tenant.bank_account.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name..."
                    class="px-4 py-2 rounded-lg text-xs border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @if (request('search'))
                    <button type="button" onclick="window.location='{{ route('tenant.bank_account.index') }}'"
                        class="absolute right-8 top-2 text-gray-400 hover:text-gray-600">&times;</button>
                @endif
                <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
            <!-- Dropdown Show Per Page -->
            <form action="{{ route('tenant.bank_account.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="per_page" class="text-xs text-gray-600">Show:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()"
                    class="text-xs rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ([10, 20, 50, 100] as $val)
                        <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Tombol Add New -->
            <a href="{{ route('tenant.bank_account.create') }}"
                class="group flex flex-col items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm"
                title="Add New User">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{-- <span class="text-xs mt-1 font-medium">Add</span> --}}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($models as $model)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                            {{ ($models->currentPage() - 1) * $models->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $model->name }} <br/> <span class="text-xs text-gray-500"> {{$model->account_number}} </span>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                            {{ $model->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('tenant.bank_account.edit', $model) }}"
                                class="text-blue-600 hover:text-blue-900">Edit</a>
                            <button type="button"
                                @click="$dispatch('open-delete-modal', {
                                        url: '{{ route('tenant.bank_account.destroy', $model) }}',
                                        name: '{{ $model->name }}'
                                    })"
                                class="text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-2 text-center text-sm text-gray-500">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">
            {{$models->links()}}
        </div>
    </div>
@endsection
