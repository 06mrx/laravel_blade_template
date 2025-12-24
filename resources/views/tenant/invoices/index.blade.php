@extends('layouts.admin')
@section('title', 'Invoice')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Invoice</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $invoice->customer->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $invoice->package?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $invoice->issue_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $invoice->due_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">{{ $invoice->formatted_amount }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="
                                    px-2 py-1 rounded-full text-xs font-medium
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                                       ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }}
                                ">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('tenant.invoices.show', $invoice) }}" class="text-blue-600 hover:underline">Lihat</a>
                                @if($invoice->status === 'unpaid')
                                    <form action="{{ route('tenant.invoices.mark-paid', $invoice) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline ml-2">✅ Bayar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $invoices->links() }}
    </div>
@endsection