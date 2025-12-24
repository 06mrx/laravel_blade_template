<!-- resources/views/tenant/mikrotik/tabs/packages.blade.php -->

<div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-medium text-gray-800">Bandwidth Packages</h3>
    <a href="{{ route('tenant.package.create', ['from_mikrotik' => $mikrotik->id]) }}"
        class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
        + Add Package
    </a>
</div>


<table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-2 text-left">Name</th>
            <th class="px-4 py-2 text-left">Type</th>
            <th class="px-4 py-2 text-left">Speed</th>
            <th class="px-4 py-2 text-left">Duration</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($mikrotik->packages as $package)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 font-medium">{{ $package->name }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                        {{ ucfirst($package->type) }}
                    </span>
                </td>
                <td class="px-4 py-2">{{ $package->speed_down }} ↓ / {{ $package->speed_up }} ↑</td>
                <td class="px-4 py-2">{{ $package->duration_days ?? 'Unlimited' }} days</td>
                {{-- <td class="px-4 py-2 text-right space-x-2">
                    <a href="{{ route('tenant.package.edit', $package) }}"
                       class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>

                    <!-- Tombol Delete dengan Modal -->
                    <button
                        type="button"
                        @click="$dispatch('open-delete-modal', {
                            url: '{{ route('tenant.package.destroy', ['package' => $package, 'from_mikrotik' => $mikrotik->id]) }}',
                            name: '{{ $package->name }}',
                            method: 'DELETE',
                            from_mikrotik: '{{ $mikrotik->id }}'
                        })"
                        class="text-red-600 hover:text-red-900 text-sm">
                        Delete
                    </button>
                </td> --}}
                <td class="px-4 py-2 text-right">
                    <div class="flex items-center justify-end space-x-1">
                        <!-- Edit Button -->
                        <a href="{{ route('tenant.package.edit', ['package' => $package, 'from_mikrotik' => $mikrotik->id]) }}"
                            class="inline-flex items-center justify-center w-8 h-8 text-sky-600 bg-sky-50 border border-sky-200 rounded-full hover:bg-sky-100 hover:text-sky-700 hover:border-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 transition-all duration-200 group"
                            title="Edit Customer">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>

                        <!-- Delete Button -->
                        <button type="button"
                            @click="$dispatch('open-delete-modal', {
                                url: '{{ route('tenant.package.destroy', ['package' => $package, 'from_mikrotik' => $mikrotik->id]) }}',
                                name: '{{ $package->name }}',
                                method: 'DELETE',
                                from_mikrotik: '{{ $mikrotik->id }}'
                            })"
                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 border border-red-200 rounded-full hover:bg-red-100 hover:text-red-700 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-all duration-200 group"
                            title="Delete Package">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada paket ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>
