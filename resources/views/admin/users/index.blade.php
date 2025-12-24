@extends('layouts.admin')
@section('title', 'Users')
@section('page-title', 'Manage Users')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-700">All Users</h2>

        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-4">
            <!-- Form Pencarian -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
                    class="w-full px-4 py-2 rounded-lg text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @if (request('search'))
                    <button type="button" onclick="window.location='{{ route('admin.users.index') }}'"
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

            <div class="flex flex-wrap justify-end gap-2">
                <!-- Dropdown Show Per Page -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center space-x-1">
                    <label for="per_page" class="text-xs text-gray-600 whitespace-nowrap">Show:</label>
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
                <a href="{{ route('admin.users.create') }}"
                    class="group flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm min-w-[40px]"
                    title="Add New User">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="ml-2 text-sm hidden sm:inline">Add</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trial Ends</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $key => $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->roles->pluck('name')->join(', ') ?: '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                            {{ $user->plan_type ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->subscription_start && $user->subscription_end)
                                {{ optional($user->subscription_start)->format('d M Y') }} - {{ optional($user->subscription_end)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ optional($user->trial_ends_at)->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="text-blue-600 hover:text-blue-900">Edit</a>
                            <button
                                @click="$dispatch('open-delete-modal', {
                        url: '{{ route('admin.users.destroy', $user) }}',
                        name: '{{ $user->name }}'
                    })"
                                class="text-red-600 hover:text-red-900">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-6 p-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection