<!-- resources/views/tenant/mikrotik/tabs/monitoring.blade.php -->

<div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-medium text-gray-800">Active Sessions & History</h3>
    <button onclick="refreshMonitoring()" class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
        Refresh
    </button>
</div>
{{-- @dd($onlineUsers ) --}}
<!-- Online Users -->
<div class="bg-white border rounded-lg p-4 mb-6">
    <h4 class="font-medium text-gray-800 mb-3">🟢 Online Users</h4>
    @if($onlineUsersCount == 0)
        <p class="text-gray-500 text-sm">Tidak ada pelanggan aktif.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">IP</th>
                        <th class="px-4 py-2 text-left">Started</th>
                        <th class="px-4 py-2 text-left">Duration</th>
                        <th class="px-4 py-2 text-left">Data Usage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($onlineUsers as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium">{{ $user?->username }}</td>
                            <td class="px-4 py-2">{{ $user?->framedipaddress }}</td>
                            <td class="px-4 py-2">{{ $user?->acctstarttime ? \Carbon\Carbon::parse($user->acctstarttime)->format('H:i:s') : '' }}</td>
                            <td class="px-4 py-2">{{ gmdate('H:i:s', $user?->acctsessiontime ?: 0) }}</td>
                            <td class="px-4 py-2">
                                {{ number_format(($user?->acctinputoctets ?? 0) / 1024 / 1024, 2) }} MB ↑ / 
                                {{ number_format(($user?->acctoutputoctets ?? 0) / 1024 / 1024, 2) }} MB ↓
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Recent Sessions -->
<div class="bg-white border rounded-lg p-4">
    <h4 class="font-medium text-gray-800 mb-3">🕒 Recent Sessions</h4>
    {{-- @if($recentSessions->count() == 0) --}}
    @if(true)
        <p class="text-gray-500 text-sm">Ganti nanti ini di tab monitoring.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">Started</th>
                        <th class="px-4 py-2 text-left">Stopped</th>
                        <th class="px-4 py-2 text-left">Duration</th>
                        <th class="px-4 py-2 text-left">Data Usage</th>
                        <th class="px-4 py-2 text-left">Cause</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentSessions as $session)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium">{{ $session->username }}</td>
                            <td class="px-4 py-2">{{ $session->acctstarttime ? \Carbon\Carbon::parse($session->acctstarttime)->format('H:i:s') : '' }}</td>
                            <td class="px-4 py-2">{{ $session->acctstoptime ? \Carbon\Carbon::parse( $session->acctstoptime)->format('d M H:i') : '' }}</td>
                            <td class="px-4 py-2">{{ $session->acctstoptime ? \Carbon\Carbon::parse($session->acctstoptime)->format('d M H:i') : '' }}</td>

                            <td class="px-4 py-2">{{ format_duration($session->acctsessiontime) }}</td>
                            <td class="px-4 py-2">
                                {{ number_format(($session->acctinputoctets ?? 0) / 1024 / 1024, 2) }} MB ↑ / 
                                {{ number_format(($session->acctoutputoctets ?? 0) / 1024 / 1024, 2) }} MB ↓
                            </td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">
                                    {{ $session->acctterminatecause }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
function refreshMonitoring() {
    window.location.reload();
}
</script>