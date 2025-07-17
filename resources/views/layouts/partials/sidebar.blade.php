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

        <!-- Audit -->
        @canany(['view-audit'])
            <li>
                <a href="{{ route('admin.audit.index') }}"
                    class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-blue-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 group-hover:text-white"  viewBox="0 0 512 512"><!-- Icon from Siemens Industrial Experience Icons by Siemens AG - https://github.com/siemens/ix-icons/blob/main/LICENSE.md --><path fill="currentColor" fill-rule="evenodd" d="m384 85.334l85.333 85.333v256H42.666l-.001-232.67c10.098 15.352 24.215 33.107 42.667 48.165L85.333 384h341.333V181.334L373.333 128l-39.736.002c-5.44-10.653-14.584-26.49-27.734-42.668zM384 320v21.334H128V320zm0-64v21.334H256v-20.371q.811-.477 1.615-.963zM181.333 42.667C278.4 42.667 320 149.334 320 149.334S278.4 256 181.333 256S42.666 149.334 42.666 149.334s41.6-106.667 138.667-106.667m0 26.667c-61.29 0-97.067 57.066-108.299 80c11.232 22.933 47.008 80 108.3 80c61.29 0 97.066-57.067 108.298-80c-11.232-22.934-47.008-80-108.299-80m0 33.333c26.804 0 48.533 20.893 48.533 46.667c0 25.773-21.729 46.666-48.533 46.666S132.8 175.107 132.8 149.334c0-25.774 21.729-46.667 48.533-46.667m0 26.667c-11.487 0-20.8 8.954-20.8 20s9.313 20 20.8 20s20.8-8.955 20.8-20s-9.312-20-20.8-20"/></svg>
                    <span>Audits</span>
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
