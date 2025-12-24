<!-- resources/views/tenant/mikrotik/tabs/customers.blade.php -->

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
    <h3 class="text-lg font-medium text-gray-800">Customers</h3>
    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-4">
        <!-- Form Pencarian -->
        <form method="GET" class="relative w-full sm:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, username..."
                   class="w-full px-4 py-2 rounded-lg text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   onsubmit="submitSearch(event)">
            @if (request('search'))
                <button type="button" onclick="clearSearch()" class="absolute right-8 top-2 text-gray-400 hover:text-gray-600">&times;</button>
            @endif
            <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>

        <!-- Dropdown Show Per Page -->
        <form method="GET" class="flex items-center space-x-1">
            <label for="per_page" class="text-xs text-gray-600 whitespace-nowrap">Show:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()"
                    class="text-xs rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                @foreach ([10, 20, 50, 100] as $val)
                    <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endforeach
            </select>
            <!-- Pertahankan search saat ganti per_page -->
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
        </form>

        <!-- Tombol Add Customer -->
        <a href="{{ route('tenant.customer.create', ['from_mikrotik' => $mikrotik->id]) }}"
           class="group flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm min-w-[40px]"
           title="Add Customer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="ml-2 text-sm hidden sm:inline">Add</span>
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-2 text-left">Name</th>
            <th class="px-4 py-2 text-left">Username</th>
            <th class="px-4 py-2 text-left">Package</th>
            <th class="px-4 py-2 text-left">IP Pool</th>
            <th class="px-4 py-2 text-left">Expires</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($customers as $customer)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 font-medium">{{ $customer->name }}</td>
                <td class="px-4 py-2">{{ $customer->username }}</td>
                <td class="px-4 py-2">{{ $customer->package?->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $customer->ipPool?->name ?? '-' }}</td>
                <td class="px-4 py-2">
                    {{ $customer->expired_at?->format('d M Y') ?? 'Unlimited' }}
                </td>
                <td class="px-4 py-2">
                    @if($customer->is_active)
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Inactive</span>
                    @endif
                </td>
                <td class="px-4 py-2 text-right space-x-2">
                    <a href="{{ route('tenant.customer.edit', $customer) }}"
                       class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                    <button
                        type="button"
                        @click="$dispatch('open-delete-modal', {
                            url: '{{ route('tenant.customer.destroy', $customer) }}',
                            name: '{{ $customer->name }}',
                            method: 'DELETE',
                            from_mikrotik: '{{ $mikrotik->id }}'
                        })"
                        class="text-red-600 hover:text-red-900 text-sm">
                        Delete
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-4 py-4 text-center text-gray-500">Tidak ada pelanggan ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{ $customers->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
</div>

<!-- Scripts -->
<script>
function clearSearch() {
    window.location.href = '{{ route('tenant.mikrotik.show', $mikrotik) }}';
}

function submitSearch(event) {
    // Pastikan form tetap di tab customers
    const form = event.target;
    const url = new URL(form.action, window.location.origin);
    url.searchParams.set('search', form.search.value);
    if (form.per_page) {
        url.searchParams.set('per_page', form.per_page.value);
    }
    window.location.href = url.toString();
}
</script>