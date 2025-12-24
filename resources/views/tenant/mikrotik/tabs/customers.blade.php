<!-- resources/views/tenant/mikrotik/tabs/customers.blade.php -->

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
    <h3 class="text-lg font-medium text-gray-800">Customers</h3>
    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-4">
        <!-- Form Pencarian -->
        <form method="GET" class="relative w-full sm:w-64" action="{{ route('tenant.mikrotik.show', $mikrotik) }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, username..."
                class="w-full px-4 py-2 rounded-lg text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="hidden" name="package_id" value="{{ request('package_id') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <input type="hidden" name="tab" value="customers">
                @if (request('search'))
                <button type="button" onclick="clearSearch()"
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

        <form method="GET" class=" flex flex-wrap gap-2">
            {{-- hidden input search --}}
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}"> 
            @endif
            <!-- Filter: Package -->
            <div class="flex items-center space-x-1">
                <label for="package_id" class="text-xs text-gray-600 whitespace-nowrap">Package:</label>
                <select name="package_id" id="package_id" onchange="this.form.submit()"
                    class="text-xs rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Packages</option>
                    @foreach ($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>
                            {{ $pkg->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter: Status -->
            <div class="flex items-center space-x-1">
                <label for="status" class="text-xs text-gray-600 whitespace-nowrap">Status:</label>
                <select name="status" id="status" onchange="this.form.submit()"
                    class="text-xs rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="terdaftar" {{ request('status') == 'terdaftar' ? 'selected' : '' }}>Terdaftar
                    </option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="isolir" {{ request('status') == 'isolir' ? 'selected' : '' }}>Isolir</option>
                </select>
            </div>
            {{-- @dd($odps) --}}
            <!-- Dropdown Show Per Page -->
            <div class="flex items-center space-x-1">
                <label for="odp_id" class="text-xs text-gray-600 whitespace-nowrap">Odp:</label>
                <select name="odp_id" id="odp_id" onchange="this.form.submit()"
                    class="text-xs rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua ODP</option>
                    @if (!empty($odc))
                        @foreach ($odc->odps as $odp)
                            <option value="{{ $odp->id }}" {{ request('odp_id', '') == $odp->id ? 'selected' : '' }}>
                                {{ $odp->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </form>

        <!-- Tombol Add Customer -->
        <a href="{{ route('tenant.customer.create', [
            'from_mikrotik' => $mikrotik->id,
            'search' => request('search'),
            'per_page' => request('per_page'),
            'package_id' => request('package_id'),
            'status' => request('status'),
        ]) }}"
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

<!-- Pertahankan semua input tersembunyi agar filter tetap aktif saat submit -->
<form id="filter-form" method="GET" style="display: none;">
    @if (request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    @if (request('package_id'))
        <input type="hidden" name="package_id" value="{{ request('package_id') }}">
    @endif
    @if (request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
</form>


<div class="overflow-x-auto">
    <span class="text-blue-500">Total Data : {{ $customers->count() }} Baris</span>
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-left">User/Password</th>
                <th class="px-4 py-2 text-left">Paket</th>
                {{-- <th class="px-4 py-2 text-left">IP Pool</th> --}}
                <th class="px-4 py-2 text-left">Jatuh Tempo</th>
                <th class="px-4 py-2 text-left">Tgl. Pemasangan</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 ">
                        <span class="font-medium">{{ $customer->name }}</span> <br>
                        <span class="text-xs text-gray-500">{{ $customer->phone }}</span> <br>
                        <span class="text-xs text-gray-500 font-thin"> <a href="http://{{ $customer->active_ip }}" target="_blank" class="text-blue-500 font-bold">{{ $customer->active_ip }}</a> </span>
                    </td>
                    <td class="px-4 py-2 text-xs"><span class="text-green-500">U</span> : {{ $customer->username }} 
                        <br>
                        <span class="text-red-500">P</span> : {{ $customer->password }}
                        <br>
                        <span class="text-yellow-500">ODC</span> : {{ $customer->odc?->name }}
                        <br>
                        <span class="text-yellow-500">ODP</span> : {{ $customer->odp?->name }}
                        <br>
                        <span class="text-yellow-500">PORT</span> : {{ $customer->port }}
                    </td>
                    <td class="px-4 py-2">{{ $customer->package?->name ?? '-' }}</td>
                    {{-- <td class="px-4 py-2">{{ $customer->ipPool?->name ?? '-' }}</td> --}}
                    <td class="px-4 py-2">
                        {{ $customer->expired_at?->format('d M Y') ?? '-' }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $customer->installation_date ?? '-' }}
                        <br>
                        <a class="text-xs" href="{{ $customer->maps_url }}">{{ !empty($customer->maps_url) ? 'lihat maps': 'belum diisi' }}</a>
                    </td>
                    <td class="px-4 py-2">
                        @if ($customer->status === 'aktif')
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">🟢 Aktif</span>
                        @elseif($customer->status === 'isolir')
                            <a class="px-2 py-1 text-xs rounded bg-red-100 text-red-800" href="{{ route('tenant.customer.toggle-status', ['customer' => $customer, 'old_status' => 'isolir', 'status' => 'aktif']) }}">🔴 Isolir</a>
                        @else
                            <a class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800" href="{{ route('tenant.customer.toggle-status', ['customer' => $customer, 'old_status' => 'terdaftar', 'status' => 'aktif']) }}">🟡 Terdaftar</a>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <!-- Detail Button -->
                            <a href="{{ route('tenant.customer.show', $customer) }}" target="_blank"
                                class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-50 border border-yellow-200 rounded-full hover:bg-yellow-100 hover:text-yellow-700 hover:border-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1 transition-all duration-200 group"
                                title="Edit Customer">
                               <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" viewBox="0 0 24 24"><!-- Icon from Huge Icons by Hugeicons - undefined --><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.016 2C18.903 2 18 4.686 18 8h2.016c.972 0 1.457 0 1.758-.335c.3-.336.248-.778.144-1.661C21.64 3.67 20.894 2 20.016 2Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M18 8.054v10.592c0 1.511 0 2.267-.462 2.565c-.755.486-1.922-.534-2.509-.904c-.485-.306-.727-.458-.996-.467c-.291-.01-.538.137-1.062.467l-1.911 1.205c-.516.325-.773.488-1.06.488s-.545-.163-1.06-.488l-1.91-1.205c-.486-.306-.728-.458-.997-.467c-.291-.01-.538.137-1.062.467c-.587.37-1.754 1.39-2.51.904C2 20.913 2 20.158 2 18.646V8.054c0-2.854 0-4.28.879-5.167C3.757 2 5.172 2 8 2h12"/><path stroke-linecap="round" d="M10 8c-1.105 0-2 .672-2 1.5s.895 1.5 2 1.5s2 .672 2 1.5s-.895 1.5-2 1.5m0-6c.87 0 1.612.417 1.886 1M10 8V7m0 7c-.87 0-1.612-.417-1.886-1M10 14v1"/></g></svg>
                            </a>
                           
                            <!-- Edit Button -->
                            <a href="{{ route('tenant.customer.edit', $customer) }}"
                                class="inline-flex items-center justify-center w-8 h-8 text-sky-600 bg-sky-50 border border-sky-200 rounded-full hover:bg-sky-100 hover:text-sky-700 hover:border-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 transition-all duration-200 group"
                                title="Edit Customer">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>

                            <!-- Delete Button -->
                            <button type="button"
                                @click="$dispatch('open-delete-modal', {
                                url: '{{ route('tenant.customer.destroy', $customer) }}',
                                name: '{{ $customer->name }}',
                                method: 'DELETE',
                                from_mikrotik: '{{ $mikrotik->id }}'
                            })"
                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 border border-red-200 rounded-full hover:bg-red-100 hover:text-red-700 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-all duration-200 group"
                                title="Delete Customer">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <td colspan="7" class="px-4 py-4 text-center text-gray-500">Tidak ada pelanggan ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $customers->appends([
            'search' => request('search'),
            'package_id' => request('package_id'),
            'status' => request('status'),
            'per_page' => request('per_page'),
            'tab' => 'customers',
            'log_page' => request('log_page'),
        ])->links() }}
</div>

<!-- Scripts -->
<script>
    function clearSearch() {
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        window.location.href = url.toString();
    }

    // Auto-submit saat pilih filter
    document.querySelectorAll('#package_id, #status, #per_page').forEach(el => {
        el.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
