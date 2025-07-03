<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Maksimal Data')</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen font-sans text-gray-900">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed md:relative inset-y-0 left-0 w-64 bg-blue-700 text-white z-30 flex flex-col transition-transform duration-200 ease-in-out">

            <!-- Logo / Judul -->
            <div class="p-4 font-bold text-xl border-b border-blue-600 flex justify-between items-center">
                <span>Maksimal Data</span>
                <!-- Tombol Close (hanya muncul di mobile) -->
                <button @click="sidebarOpen = false" class="text-white md:hidden">
                    &times;
                </button>
            </div>

            <!-- Menu -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                @include('layouts.partials.sidebar')
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- HEADER -->
            <header class="h-16 px-6 bg-white shadow-sm flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center">
                    <!-- Hamburger Button (only visible on mobile) -->
                    <button @click="sidebarOpen = true" class="text-gray-500 md:hidden mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <h1 class="text-lg font-semibold">@yield('page-title', '')</h1>
                </div>

                <!-- User Info + Logout -->
                <!-- Profil Dropdown -->
                <div x-data="{ open: false }" class="relative ml-3">
                    <div>
                        <button @click="open = !open" type="button"
                            class="flex items-center text-sm focus:outline-none" id="user-menu-button"
                            aria-expanded="false" aria-haspopup="true">
                            <!-- Avatar -->
                            <span class="sr-only">Open user menu</span>
                            <div
                                class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                    </div>

                    <!-- Dropdown menu -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">

                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            role="menuitem" tabindex="-1" @click="open = false">Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            role="menuitem" tabindex="-1" @click="open = false">Settings</a>
                        <form method="POST" action="{{ route('logout') }}"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            @csrf
                            <button type="submit" class="w-full text-left">Sign out</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="p-6 bg-gray-50 overflow-auto">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-20"
            @click.away="sidebarOpen = false"></div>

    </div>
    @include('components.confirm-delete-modal')
    @include('components.toast')

    <!-- Validasi Form Error -->
    @if ($errors->any())
        <div x-data="{ show: false }" x-init="() => {
            show = true;
            setTimeout(() => show = false, 5000);
        }" x-show="show"
            class="fixed top-4 right-4 max-w-sm w-full bg-red-500 text-white rounded-lg shadow-lg p-4 z-50 transition transform duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Terjadi kesalahan, mohon periksa inputan.</span>
                </div>
                <button @click="show = false" class="text-white">&times;</button>
            </div>
        </div>
    @endif
    @stack('scripts')
</body>

</html>
