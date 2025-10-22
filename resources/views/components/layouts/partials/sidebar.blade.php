{{-- Sidebar Hauptcontainer --}}
<aside class="sidebar fixed top-0 left-0 h-screen w-[260px] bg-white p-5 border-r border-gray-200 z-50 
             transition-all duration-300 ease-in-out 
             group-[.sidebar-collapsed]/sidebar:w-[90px] 
             max-lg:-translate-x-full 
             group-[.sidebar-mobile-open]/sidebar:translate-x-0 overflow-y-auto">

    {{-- Sidebar-Header --}}
    <div class="sidebar-header">
        <a class="flex items-center justify-center">
            {{-- Logo Anda bisa diletakkan di sini --}}
            <h2
                class="text-[#d90000] text-center mt-0 mb-8 font-bold text-2xl group-[.sidebar-collapsed]/sidebar:hidden">
                PT. JAI
            </h2>
            {{-- Logo kecil saat sidebar diciutkan --}}
            <img src="https://placehold.co/40x40/d90000/ffffff?text=Y" alt="Yazaki Logo"
                class="hidden group-[.sidebar-collapsed]/sidebar:block mb-8">
        </a>
    </div>

    {{-- Navigationsmenü --}}
    <nav>
        <ul class="space-y-1">
            <li
                class="text-xs text-gray-500 uppercase px-3 py-2 font-bold group-[.sidebar-collapsed]/sidebar:text-center group-[.sidebar-collapsed]/sidebar:text-[10px] group-[.sidebar-collapsed]/sidebar:p-0">
                MENU
            </li>
            {{-- Dashboard Link --}}
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-3 rounded-md font-medium text-gray-700 cursor-pointer hover:bg-gray-100
                          group-[.sidebar-collapsed]/sidebar:justify-center
                          {{ request()->routeIs('dashboard') ? 'bg-[#d90000] text-white hover:bg-red-700' : '' }}">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span class="group-[.sidebar-collapsed]/sidebar:hidden">Dashboard</span>
                </a>
            </li>
            {{-- Documents Link --}}
            <li>
                <a href="{{ route('documents') }}" class="flex items-center gap-4 p-3 rounded-md font-medium text-gray-700 cursor-pointer hover:bg-gray-100
                          group-[.sidebar-collapsed]/sidebar:justify-center
                          {{ request()->routeIs('documents') ? 'bg-[#d90000] text-white hover:bg-red-700' : '' }}">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                        <polyline points="13 2 13 9 20 9"></polyline>
                    </svg>
                    <span class="group-[.sidebar-collapsed]/sidebar:hidden">Documents</span>
                </a>
            </li>
            {{-- Documents Out Link --}}
            <li>
                <a href="{{ route('document-out') }}" class="flex items-center gap-4 p-3 rounded-md font-medium text-gray-700 cursor-pointer hover:bg-gray-100
                          group-[.sidebar-collapsed]/sidebar:justify-center
                          {{ request()->routeIs('document-out') ? 'bg-[#d90000] text-white hover:bg-red-700' : '' }}">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <span class="group-[.sidebar-collapsed]/sidebar:hidden">Documents Out</span>
                </a>
            </li>
            {{-- Licenses Link --}}
            <li>
                <a href="{{ route('licenses') }}" class="flex items-center gap-4 p-3 rounded-md font-medium text-gray-700 cursor-pointer hover:bg-gray-100
                            group-[.sidebar-collapsed]/sidebar:justify-center
                            {{ request()->routeIs('licenses') ? 'bg-[#d90000] text-white hover:bg-red-700' : '' }}">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4" />
                        <path d="M9 18c-4.51 2-5-2-7-2" />
                    </svg>
                    <span class="group-[.sidebar-collapsed]/sidebar:hidden">Licenses</span>
                </a>
            </li>

            {{-- Manage Dropdown --}}
            <li x-data="{ open: {{ request()->routeIs('manage.*') ? 'true' : 'false' }} }">
                <div @click="open = !open" class="flex items-center justify-between gap-4 p-3 rounded-md font-medium text-gray-700 hover:bg-gray-100 cursor-pointer
                            group-[.sidebar-collapsed]/sidebar:justify-center
                            {{ request()->routeIs('manage.*') ? 'bg-gray-100' : '' }}">
                    <div class="flex items-center gap-4">
                        <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 10v6M2 10v6M12 2v20M20.39 4.61a2.3 2.3 0 0 0-3.25 0L12 9.68l-5.14-5.07a2.3 2.3 0 0 0-3.25 0L2 6.27a2.3 2.3 0 0 0 0 3.25L7.14 14.7 12 19.8l5.14-5.09 5.14-5.14a2.3 2.3 0 0 0 0-3.25l-1.61-1.61Z">
                            </path>
                        </svg>
                        <span class="group-[.sidebar-collapsed]/sidebar:hidden">Manage</span>
                    </div>
                    <svg class="h-4 w-4 shrink-0 transition-transform duration-200 group-[.sidebar-collapsed]/sidebar:hidden"
                        :class="{ 'rotate-90': open }" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </div>
                {{-- Dropdown Menu --}}
                <ul x-show="open" x-transition class="mt-1 space-y-1 pl-5 group-[.sidebar-collapsed]/sidebar:hidden">
                    <li><a href="{{ route('manage.user') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.user') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">User</a>
                    </li>
                    <li><a href="{{ route('manage.role') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.role') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Role</a>
                    </li>
                    <li><a href="{{ route('manage.permission') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.permission') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Permission</a>
                    </li>
                    <li><a href="{{ route('manage.employee') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.employee') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Employees</a>
                    </li>
                    <li><a href="{{ route('manage.position') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.position') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Positions</a>
                    </li>
                    <li><a href="{{ route('manage.department') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.department') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Departments</a>
                    </li>
                    <li><a href="{{ route('manage.category') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.category') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Categories</a>
                    </li>
                    <li><a href="{{ route('manage.section') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.section') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Sections</a>
                    </li>
                    <li><a href="{{ route('manage.field') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.field') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Fields</a>
                    </li>
                    <li><a href="{{ route('manage.document-type') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.document-type') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Document
                            Types</a>
                    </li>
                    <li><a href="{{ route('manage.action-frequency-unit') }}"
                            class="block p-2 rounded-md text-sm cursor-pointer {{ request()->routeIs('manage.action-frequency-unit') ? 'text-[#d90000] font-semibold' : 'text-gray-600 hover:text-gray-900' }}">Action
                            Frequency Units</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>