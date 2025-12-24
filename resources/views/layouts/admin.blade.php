<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Maksimal Data')</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen font-sans text-gray-900">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed md:relative inset-y-0 left-0 w-64 bg-gradient-to-b from-sky-600 via-blue-700 to-blue-800 text-white z-30 flex flex-col transition-all duration-300 ease-in-out shadow-2xl backdrop-blur-sm">

            <!-- Logo / Header -->
            <div
                class="relative p-5 border-b border-sky-500/30 bg-gradient-to-r from-sky-500/20 to-blue-600/20 backdrop-blur-sm">
                <!-- Background decoration -->
                <div class="absolute inset-0 bg-gradient-to-r from-sky-400/10 to-blue-500/10"></div>

                <div class="relative flex justify-between items-center">
                    <!-- Logo with icon -->
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-sky-300 to-blue-400 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="font-bold text-xl bg-gradient-to-r from-sky-100 to-blue-100 bg-clip-text text-transparent">
                                {{ env('APP_NAME', 'xBilling') }}
                            </h1>
                            <p class="text-sky-200 text-xs font-medium">Lorem ipsum</p>
                        </div>
                    </div>

                    <!-- Close button (mobile only) -->
                    <button @click="sidebarOpen = false"
                        class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg bg-sky-500/20 hover:bg-sky-500/30 transition-all duration-200 group">
                        <svg class="w-5 h-5 text-sky-200 group-hover:text-white transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav
                class="flex-1 p-4 space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-sky-500/50 scrollbar-track-transparent">
                <ul class="space-y-1">
                    @role('admin')
                        <!-- Dashboard -->
                        <li>
                            <a href="/dashboard"
                                class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                <div class="w-5 h-5 mr-3 text-sky-300 group-hover:text-sky-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <span class="font-medium">Dashboard</span>
                                <div
                                    class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('dashboard') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                </div>
                            </a>
                        </li>

                        <!-- Roles -->
                        @canany(['view-role', 'create-role'])
                            <li>
                                <a href="{{ route('admin.roles.index') }}"
                                    class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('admin.roles.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                    <div class="w-5 h-5 mr-3 text-sky-300 group-hover:text-sky-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Roles</span>
                                    <div
                                        class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('admin.roles.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                    </div>
                                </a>
                            </li>
                        @endcanany

                        <!-- Permissions -->
                        @canany(['view-permission', 'create-permission'])
                            <li>
                                <a href="{{ route('admin.permissions.index') }}"
                                    class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('admin.permissions.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                    <div class="w-5 h-5 mr-3 text-sky-300 group-hover:text-sky-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Permissions</span>
                                    <div
                                        class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('admin.permissions.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                    </div>
                                </a>
                            </li>
                        @endcanany

                        <!-- Audit -->
                        @canany(['view-audit'])
                            <li>
                                <a href="{{ route('admin.audit.index') }}"
                                    class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('admin.audit.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                    <div class="w-5 h-5 mr-3 text-sky-300 group-hover:text-sky-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="m384 85.334l85.333 85.333v256H42.666l-.001-232.67c10.098 15.352 24.215 33.107 42.667 48.165L85.333 384h341.333V181.334L373.333 128l-39.736.002c-5.44-10.653-14.584-26.49-27.734-42.668zM384 320v21.334H128V320zm0-64v21.334H256v-20.371q.811-.477 1.615-.963zM181.333 42.667C278.4 42.667 320 149.334 320 149.334S278.4 256 181.333 256S42.666 149.334 42.666 149.334s41.6-106.667 138.667-106.667m0 26.667c-61.29 0-97.067 57.066-108.299 80c11.232 22.933 47.008 80 108.3 80c61.29 0 97.066-57.067 108.298-80c-11.232-22.934-47.008-80-108.299-80m0 33.333c26.804 0 48.533 20.893 48.533 46.667c0 25.773-21.729 46.666-48.533 46.666S132.8 175.107 132.8 149.334c0-25.774 21.729-46.667 48.533-46.667m0 26.667c-11.487 0-20.8 8.954-20.8 20s9.313 20 20.8 20s20.8-8.955 20.8-20s-9.312-20-20.8-20" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Audits</span>
                                    <div
                                        class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('admin.audit.*') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                    </div>
                                </a>
                            </li>
                        @endcanany

                        <!-- User Management Section -->
                        @canany(['view-user', 'create-user'])
                            <li class="mt-6">
                                <div class="flex items-center px-4 py-2 mb-2">
                                    <div class="flex-1 border-t border-sky-500/30"></div>
                                    <span
                                        class="px-3 text-xs uppercase font-semibold text-sky-300 bg-gradient-to-r from-sky-500/20 to-blue-600/20 rounded-full py-1">User
                                        Management</span>
                                    <div class="flex-1 border-t border-sky-500/30"></div>
                                </div>
                                <ul class="space-y-1 pl-4 border-l-2 border-sky-500/30 ml-4">
                                    @can('view-user')
                                        <li>
                                            <a href="{{ route('admin.users.index') }}"
                                                class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                                <div class="w-4 h-4 mr-3 text-sky-400 group-hover:text-sky-200 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium">List Users</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    @else
                        <!-- Non-Admin Dashboard -->
                        <li>
                            <a href="/dashboard"
                                class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                <div class="w-5 h-5 mr-3 text-sky-300 group-hover:text-sky-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <span class="font-medium">Dashboard</span>
                                <div
                                    class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('dashboard') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                </div>
                            </a>
                        </li>

                        <!-- Mikrotik -->
                        @canany(['view-mikrotik'])
                            <li>
                                <a href="{{ route('tenant.mikrotik.index') }}"
                                    class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('tenant.mikrotik.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                    <div
                                        class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.mikrotik.*') ? 'text-sky-100' : 'text-sky-300 group-hover:text-sky-100' }} transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                                            <path
                                                d="M5.525 3.025a3.5 3.5 0 0 1 4.95 0a.5.5 0 1 0 .707-.707a4.5 4.5 0 0 0-6.364 0a.5.5 0 0 0 .707.707" />
                                            <path
                                                d="M6.94 4.44a1.5 1.5 0 0 1 2.12 0a.5.5 0 0 0 .708-.708a2.5 2.5 0 0 0-3.536 0a.5.5 0 0 0 .707.707Z" />
                                            <path
                                                d="M2.5 11a.5.5 0 1 1 0-1a.5.5 0 0 1 0 1m4.5-.5a.5.5 0 1 0 1 0a.5.5 0 0 0-1 0m2.5.5a.5.5 0 1 1 0-1a.5.5 0 0 1 0 1m1.5-.5a.5.5 0 1 0 1 0a.5.5 0 0 0-1 0m2 0a.5.5 0 1 0 1 0a.5.5 0 0 0-1 0" />
                                            <path
                                                d="M2.974 2.342a.5.5 0 1 0-.948.316L3.806 8H1.5A1.5 1.5 0 0 0 0 9.5v2A1.5 1.5 0 0 0 1.5 13H2a.5.5 0 0 0 .5.5h2A.5.5 0 0 0 5 13h6a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5h.5a1.5 1.5 0 0 0 1.5-1.5v-2A1.5 1.5 0 0 0 14.5 8h-2.306l1.78-5.342a.5.5 0 1 0-.948-.316L11.14 8H4.86zM14.5 9a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5z" />
                                            <path d="M8.5 5.5a.5.5 0 1 1-1 0a.5.5 0 0 1 1 0" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Perangkat</span>
                                    <div
                                        class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('tenant.mikrotik.index') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                    </div>
                                </a>
                            </li>
                        @endcanany
                        @canany(['view-bankaccount'])
                            <li>
                                <a href="{{ route('tenant.bank_account.index') }}"
                                    class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('tenant.bank_account.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                    <div
                                        class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.bank_account.*') ? 'text-sky-100' : 'text-sky-300 group-hover:text-sky-100' }} transition-colors">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><!-- Icon from Iconoir by Luca Burgio - https://github.com/iconoir-icons/iconoir/blob/main/LICENSE --><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.5L12 4l9 5.5M5 20h14M10 9h4m-8 8v-5m4 5v-5m4 5v-5m4 5v-5"/></svg>
                                    </div>
                                    <span class="font-medium">Akun Bank</span>
                                    <div
                                        class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('tenant.bank_account.index') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                    </div>
                                </a>
                            </li>
                        @endcanany
                         @canany(['view-odc'])
                            <li>
                                <a href="{{ route('tenant.odc.index') }}"
                                    class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('tenant.odc.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                    <div
                                        class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.odc.*') ? 'text-sky-100' : 'text-sky-300 group-hover:text-sky-100' }} transition-colors">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><!-- Icon from Iconoir by Luca Burgio - https://github.com/iconoir-icons/iconoir/blob/main/LICENSE --><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.5L12 4l9 5.5M5 20h14M10 9h4m-8 8v-5m4 5v-5m4 5v-5m4 5v-5"/></svg>
                                    </div>
                                    <span class="font-medium">ODC</span>
                                    <div
                                        class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('tenant.bank_account.index') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                    </div>
                                </a>
                            </li>
                        @endcanany
                        {{-- @canany(['view-billingcycle'])
                            <li>
                                <a href="{{ route('tenant.billing_cycle.index') }}"
                                    class="group flex items-center px-4 py-3 rounded-xl text-sky-100 transition-all duration-300 transform
                                        {{ request()->routeIs('tenant.billing_cycle.*') ? 'bg-gradient-to-r from-sky-500/30 to-blue-500/30 text-white scale-105 shadow-lg' : 'hover:text-white hover:bg-gradient-to-r hover:from-sky-500/30 hover:to-blue-500/30 hover:scale-105 hover:shadow-lg' }}">
                                    <div
                                        class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.billing_cycle.*') ? 'text-sky-100' : 'text-sky-300 group-hover:text-sky-100' }} transition-colors">
                                       <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><!-- Icon from Akar Icons by Arturo Wibawa - https://github.com/artcoholic/akar-icons/blob/master/LICENSE --><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M22 12c0 6-4.39 10-9.806 10C7.792 22 4.24 19.665 3 16m-1-4C2 6 6.39 2 11.807 2C16.208 2 19.758 4.335 21 8"/><path d="m7 17l-4-1l-1 4M17 7l4 1l1-4"/></g></svg>
                                    </div>
                                    <span class="font-medium">Siklus Tagihan    </span>
                                    <div
                                        class="ml-auto w-2 h-2 bg-sky-400 rounded-full transition-opacity {{ request()->routeIs('tenant.billing_cycle.index') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                                    </div>
                                </a>
                            </li>
                        @endcanany --}}
                    @endrole
                </ul>
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-sky-500/30 bg-gradient-to-r from-sky-500/10 to-blue-600/10">
                <div
                    class="flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-sky-500/20 to-blue-600/20 hover:from-sky-500/30 hover:to-blue-600/30 transition-all duration-300 cursor-pointer group">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-sky-300 to-blue-400 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white group-hover:text-sky-100 transition-colors">
                            John Doe
                        </p>
                        <p class="text-xs text-sky-200 truncate">
                            john@example.com
                        </p>
                    </div>
                    <div class="w-4 h-4 text-sky-300 group-hover:text-sky-100 transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Floating decorative elements -->
            <div class="absolute top-20 right-4 w-2 h-2 bg-sky-300/30 rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-8 w-1 h-1 bg-blue-300/40 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute bottom-40 right-6 w-1.5 h-1.5 bg-sky-400/20 rounded-full animate-pulse delay-2000">
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- HEADER -->
            <header
                class="min-h-16 px-6 bg-gradient-to-r from-sky-50 to-blue-50 backdrop-blur-md border-b border-sky-100/50 shadow-sm flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center">
                    <!-- Hamburger Button (only visible on mobile) -->
                    <button @click="sidebarOpen = true"
                        class="text-sky-600 hover:text-sky-800 md:hidden mr-4 p-1 rounded-lg hover:bg-sky-100/50 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <h1
                        class="text-lg font-semibold text-slate-800 bg-gradient-to-r from-sky-700 to-blue-600 bg-clip-text text-transparent">
                        @yield('page-title', '')
                    </h1>
                </div>

                <!-- User Info + Logout -->
                <!-- Profil Dropdown -->
                <div x-data="{ open: false }" class="relative ml-3">
                    <div>
                        <button @click="open = !open" type="button"
                            class="flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 focus:ring-offset-2 rounded-full transition-all duration-200 hover:shadow-md"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <!-- Avatar -->
                            <span class="sr-only">Open user menu</span>
                            <div
                                class="h-8 w-8 rounded-full bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center text-white text-xs font-medium uppercase shadow-md ring-2 ring-white hover:shadow-lg transition-shadow duration-200">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                    </div>

                    <!-- Dropdown menu -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-xl bg-white/95 backdrop-blur-md py-2 shadow-xl ring-1 ring-sky-100 border border-sky-100/50 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                        tabindex="-1">

                        <a href="/profile"
                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-gradient-to-r hover:from-sky-50 hover:to-blue-50 hover:text-sky-800 transition-all duration-200 mx-2 rounded-lg"
                            role="menuitem" tabindex="-1" @click="open = false">
                            <svg class="w-4 h-4 mr-3 text-sky-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>

                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-gradient-to-r hover:from-sky-50 hover:to-blue-50 hover:text-sky-800 transition-all duration-200 mx-2 rounded-lg"
                            role="menuitem" tabindex="-1" @click="open = false">
                            <svg class="w-4 h-4 mr-3 text-sky-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>

                        <div class="border-t border-sky-100 my-2"></div>

                        <form method="POST" action="{{ route('logout') }}" class="block w-full text-left">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 hover:text-red-700 transition-all duration-200 mx-2 rounded-lg">
                                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Sign out
                            </button>
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
    @include('components.expire-command-modal')
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
