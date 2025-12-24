@extends('layouts.admin')
@section('title', 'Edit Web Proxy HTML - ' . $mikrotik->name)

@push('scripts')

    <script>
   


        function openPreview() {
            window.open("{{ route('tenant.webproxy.preview', $mikrotik) }}", "preview", "width=800,height=600");
        }
    </script>
@endpush

@section('content')
    {{-- @dd($html) --}}
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Web Proxy HTML</h1>
            <div class="flex gap-3">
                <button type="button" onclick="openPreview()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    📱 Preview
                </button>
                <a href="{{ route('tenant.mikrotik.show', $mikrotik) }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- <pre>
            {{$html}}
        </pre> --}}
        <form action="{{ route('tenant.webproxy.update', $mikrotik) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="html_content" class="block text-sm font-medium text-gray-700 mb-2">
                    Edit HTML (Error Page)
                </label>
                <textarea id="html_content" rows="15" name="html_content" class="w-full">{{ old('html_content', $html) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
