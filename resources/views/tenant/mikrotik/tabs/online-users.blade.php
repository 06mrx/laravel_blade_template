    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-800">Active PPPoE Sessions</h3>
        <button
            onclick="refreshOnlineUsers()"
            class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
            Refresh
        </button>
    </div>

    @if($onlineUsers->isEmpty())
        <p class="text-gray-500 text-sm">Tidak ada pelanggan aktif saat ini.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">IP Address</th>
                        <th class="px-4 py-2 text-left">Uptime</th>
                        <th class="px-4 py-2 text-left">Caller ID (MAC)</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($onlineUsers as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium">{{ $user['name'] }}</td>
                            <td class="px-4 py-2 font-mono text-blue-600">{{ $user['address'] }}</td>
                            <td class="px-4 py-2">{{ $user['uptime'] }}</td>
                            <td class="px-4 py-2 font-mono text-xs">{{ $user['caller_id'] }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <!-- Tombol Remote Access -->
                                <a href="http://{{ $user['address'] }}"
                                   target="_blank"
                                   onclick="event.preventDefault();
                                       if (confirm('Buka modem di {{ $user['address'] }}?')) {
                                           window.open('http://{{ $user['address'] }}', '_blank');
                                       }"
                                   class="text-green-600 hover:text-green-900 text-sm font-medium">
                                    🔧 Remote
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
<script>
// Tab Navigation (sudah ada)
document.querySelectorAll('.tab-link').forEach(tab => {
    tab.addEventListener('click', function (e) {
        e.preventDefault();
        const target = this.getAttribute('href').substring(1);

        document.querySelectorAll('.tab-content').forEach(c => {
            c.classList.add('hidden');
        });
        document.getElementById(target).classList.remove('hidden');

        document.querySelectorAll('.tab-link').forEach(t => {
            t.classList.remove('active', 'bg-white');
        });
        this.classList.add('active', 'bg-white');
    });
});

// Refresh Online Users (reload halaman)
function refreshOnlineUsers() {
    const url = new URL(window.location.href);
    url.searchParams.set('tab', 'online-users');
    window.location.href = url.toString();
}
</script>