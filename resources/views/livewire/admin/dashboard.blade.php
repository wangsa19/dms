<div>
    <div class="text-sm text-gray-500 mb-4">Dashboards > {{ $role === 'Admin' ? 'Document Analytics' : 'Department
        Overview' }}</div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        {{ $role === 'Admin' ? 'DOCUMENT ANALYTICS' : 'DOCUMENT DASHBOARD' }}
    </h1>

    @if($role === 'Admin')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- Card: TOTAL USERS --}}
        <div
            class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm transition-colors">
            <h6 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">TOTAL USERS</h6>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $totalUsers }} <span
                            class="text-base font-medium text-gray-500 dark:text-gray-400">Users</span>
                    </p>
                    <a wire:navigate href="{{ route('manage.user') }}"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 mt-4 block transition">
                        Manage all users
                    </a>
                </div>
                <svg class="h-10 w-10 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        {{-- Card: TOTAL LICENSES --}}
        <div
            class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm transition-colors">
            <h6 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">TOTAL LICENSES</h6>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $totalLicenses }} <span
                            class="text-base font-medium text-gray-500 dark:text-gray-400">Licenses</span>
                    </p>
                    <a wire:navigate href="{{ route('licenses') }}"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 mt-4 block transition">
                        View all licenses
                    </a>
                </div>
                <svg class="h-10 w-10 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        {{-- Card 1: ALL DOCUMENTS --}}
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <h6 class="text-sm font-medium text-gray-500 mb-2">ALL DOCUMENTS</h6>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-3xl font-bold">{{ $totalDocuments }} <span
                            class="text-base font-medium text-gray-500">Docs</span></p>
                    <div class="relative group">
                        <button class="text-sm text-gray-500 hover:text-yazaki-red mt-4 block focus:outline-none">
                            View all documents ▼
                        </button>
                        <div
                            class="absolute left-0 mt-2 w-48 bg-white border border-gray-100 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <a href="{{ route('documents') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Documents</a>
                            <a href="{{ route('licenses') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Licenses</a>
                        </div>
                    </div>
                </div>
                <svg class="h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
        </div>

        {{-- Card 2: DOCUMENTS OUT --}}
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <h6 class="text-sm font-medium text-gray-500 mb-2">DOCUMENTS OUT</h6>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-3xl font-bold">{{ $totalDocsOut }} <span
                            class="text-base font-medium text-gray-500">Docs</span></p>
                    <a href="{{ route('document-out') }}"
                        class="text-sm text-gray-500 hover:text-yazaki-red mt-4 block">View all documents</a>
                </div>
                <svg class="h-10 w-10 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
            </div>
        </div>

        {{-- Card 3: TODAY DOCS, OUT --}}
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <h6 class="text-sm font-medium text-gray-500 mb-2">TODAY DOCS, OUT</h6>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-3xl font-bold">{{ $todayDocsOut }} <span
                            class="text-base font-medium text-gray-500">Docs</span></p>
                    <a href="{{ route('document-out') }}"
                        class="text-sm text-gray-500 hover:text-yazaki-red mt-4 block">View all documents</a>
                </div>
                <svg class="h-10 w-10 text-yellow-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <line x1="12" y1="15" x2="12" y2="20"></line>
                    <polyline points="15 17 12 20 9 17"></polyline>
                </svg>
            </div>
        </div>

        {{-- Card 4: TODAY DOCS, RETURN --}}
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <h6 class="text-sm font-medium text-gray-500 mb-2">TODAY DOCS, RETURN</h6>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-3xl font-bold">{{ $todayDocsReturn }} <span
                            class="text-base font-medium text-gray-500">Docs</span></p>
                    <a href="{{ route('document-out') }}"
                        class="text-sm text-gray-500 hover:text-yazaki-red mt-4 block">View all documents</a>
                </div>
                <svg class="h-10 w-10 text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="15"></line>
                    <polyline points="15 18 12 15 9 18"></polyline>
                </svg>
            </div>
        </div>
    </div>

    {{-- CHARTS GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Documents per Category</h5>
            <div class="p-5">
                <div id="category-chart" data-labels='@json($categoryData->pluck("name"))'
                    data-series='@json($categoryData->pluck("documents_count"))'></div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Documents per Type</h5>
            <div class="p-5">
                <div id="type-chart" data-labels='@json($typeData->pluck("name"))'
                    data-series='@json($typeData->pluck("documents_count"))'></div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Documents per Department</h5>
            <div class="p-5">
                <div id="department-chart" data-labels='@json($departmentData->pluck("name"))'
                    data-series='@json($departmentData->pluck("documents_count"))'></div>
            </div>
        </div>
    </div>

    {{-- BOTTOM GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Documents Out & Return</h5>
            <div class="p-5">
                <div id="docs-out-return-chart" data-labels='@json($outReturnLabels)'
                    data-series='@json($outReturnSeries)'></div>
            </div>
        </div>
        <div class="lg:col-span-1 bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Last Updated Documents</h5>
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left bg-gray-50">
                            <tr>
                                <th class="p-3 font-semibold">Document Name</th>
                                <th class="p-3 font-semibold">Type</th>
                                <th class="p-3 font-semibold">Upload At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestDocuments as $doc)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-3">{{ $doc->name_id }}</td>
                                <td class="p-3">{{ $doc->documentType->name ?? '-' }}</td>
                                <td class="p-3">{{ $doc->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian @else (Supervisor Role) --}}
    @else
    {{-- 1. STATS GRID (Baris Atas: Total Dokumen & Total Lisensi) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Card: Total Dokumen --}}
        <div
            class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-5">
            <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500 dark:text-gray-400 font-medium text-sm">Total Dokumen</h3>
                <p class="text-4xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalDocs }}</p>
            </div>
        </div>

        {{-- Card: Total Lisensi --}}
        <div
            class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-5">
            <div class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500 dark:text-gray-400 font-medium text-sm">Total Lisensi</h3>
                <p class="text-4xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalLicenses }}</p>
            </div>
        </div>
    </div>

    {{-- 2. DETAIL GRID (Baris Bawah: Reminder Lisensi & Dokumen Terbaru) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Card: Reminder Lisensi (2 Kolom di Layar Lebar) --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-red-200 dark:border-red-900/50 shadow-sm lg:col-span-2 overflow-hidden">
            <div
                class="bg-red-50 dark:bg-red-900/20 px-5 py-4 border-b border-red-100 dark:border-red-900/30 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <h3 class="font-bold text-red-800 dark:text-red-400">Reminder Lisensi</h3>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($expiringLicenses as $license)
                <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex justify-between items-center transition">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $license->name_id }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Berakhir: {{
                            \Carbon\Carbon::parse($license->end_date)->format('d M Y') }}</p>
                    </div>
                    <span
                        class="px-3 py-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded-full text-xs font-semibold">Expired
                        Soon</span>
                </li>
                @empty
                <li class="p-8 text-center text-gray-500 dark:text-gray-400">Tidak ada reminder lisensi saat ini.</li>
                @endforelse
            </ul>
        </div>

        {{-- Card: Dokumen Terbaru (1 Kolom) --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <h5
                class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 py-4 px-5">
                Dokumen Terbaru</h5>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($latestDocs as $doc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="p-4">
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $doc->name_id }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{
                                    $doc->created_at->diffForHumans() }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="p-8 text-center text-gray-500 dark:text-gray-400">Belum ada dokumen.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>