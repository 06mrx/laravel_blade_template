@extends('layouts.admin')
@section('title', 'Detail Customer')
@section('page-title', 'Detail Customer')

@section('content')
    {{-- <div class="flex w-full justify-end">
        <a class=" bg-sky-500 text-white rounded-lg px-2 py-1.5 mb-4" href="{{ route('') }}"> Kembali</a>
    </div> --}}
    <table>
        <tr>
            <td>Nama</td>
            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $customer->name }}
            </td>
        </tr>
        <tr>
            <td>Email</td>
            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $customer->email }}
            </td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $customer->address }}
            </td>
        </tr>
        <tr>
            <td>Paket</td>
            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $customer->package->name }}
            </td>
        </tr>
    </table>
    <div class="w-full mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.
                            Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl.
                            Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($invoices as $key => $model)
                        <tr
                            class="hover:bg-gray-50 transition duration-150 {{ $model->status == 'paid' ? 'bg-green-100' : 'bg-red-100' }}">
                            <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                                {{ ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $model->invoice_number }} <br /> <span class="text-xs text-gray-500">
                                    {{ $model->formatted_amount }} </span>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $model->due_date }}
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $model->status }}
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                {{-- <button type="button"
                                class="{{ $model->status != "paid" ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900' }}">
                                {{ $model->status == "paid" ? 'Void' : 'Sudah Bayar' }}
                            </button> --}}
                                @if ($model->status != 'paid')
                                    <a href="{{ route('tenant.invoices.markAsPaid', $model) }}">Tandai Sudah Bayar</a>
                                @else
                                    sfdsdf
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-2 text-center text-sm text-gray-500">Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
@endsection
