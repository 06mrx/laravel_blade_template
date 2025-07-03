<ul class="space-y-1">
    @role('admin')
        <!-- Dashboard -->
        <li>
            <a href="/dashboard" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-blue-600 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 group-hover:text-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Roles -->
        @canany(['view-role', 'create-role'])
            <li>
                <a href="{{ route('admin.roles.index') }}"
                    class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-blue-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 group-hover:text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857" />
                    </svg>
                    <span>Roles</span>
                </a>
            </li>
        @endcanany

        <!-- Permissions -->
        @canany(['view-permission', 'create-permission'])
            <li>
                <a href="{{ route('admin.permissions.index') }}"
                    class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-blue-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 group-hover:text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Permissions</span>
                </a>
            </li>
        @endcanany

        <!-- User Management -->
        @canany(['view-user', 'create-user'])
            <li class="mt-4">
                <span class="text-xs uppercase font-semibold text-blue-200">User Management</span>
                <ul class="mt-2 space-y-1 pl-2 border-l border-blue-500">
                    @can('view-user')
                        <li><a href="{{ route('admin.users.index') }}"
                                class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-blue-600 group">List Users</a></li>
                    @endcan
                </ul>
            </li>
        @endcanany
    @else
        <li>
            <a href="/dashboard" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-blue-600 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 group-hover:text-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>
        </li>
    @endrole
</ul>
