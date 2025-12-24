<div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-800">Notification Logs</h3>
    </div>

    @if($notificationLogs->isEmpty())
        <p class="text-gray-500 text-sm">Belum ada log notifikasi.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Pelanggan</th>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">Jenis</th>
                        <th class="px-4 py-2 text-left">Subject</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Error</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($notificationLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium">
                                {{ $log->customer?->name ?? '[Pelanggan dihapus]' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $log->customer?->username ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                @if($log->type === 'expiring_soon')
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Reminder</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Expired</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-800">{{ Str::limit($log->subject, 40) }}</td>
                            <td class="px-4 py-2">
                                @if($log->success)
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">✅ Sukses</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">❌ Gagal</span>
                                @endif
                            </td>
                                                        <td class="px-4 py-2 text-gray-800">{{ $log->error }}</td>

                            <td class="px-4 py-2 text-xs text-gray-500">
                                {{ $log->sent_at?->format('d M H:i') ?? $log->created_at->format('d M H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-4">
           {{-- {{ $notificationLogs->withQueryString()->setPageName('log_page')->appends(['tab' => request('tab')])->links() }} --}}
            {{ $notificationLogs->appends(
                [
                    'tab' => 'logs',
                    'log_page' => request('log_page', 1)
                ]
                )->setPageName('log_page')->links('vendor.pagination.tailwind') }}
        </div>
    @endif