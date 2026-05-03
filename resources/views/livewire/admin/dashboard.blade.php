<div>
    <div class="text-sm text-gray-500 mb-4">Dashboards > Document Analytics</div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">DOCUMENT ANALYTICS</h1>

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
                {{-- Update bagian div chart category --}}
                <div id="category-chart" data-labels='@json($categoryData->pluck("name"))'
                    data-series='@json($categoryData->pluck("documents_count"))'>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Documents per Type</h5>
            <div class="p-5">
                <div id="type-chart" data-labels='@json($typeData->pluck("name"))'
                    data-series='@json($typeData->pluck("documents_count"))'>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Documents per Department</h5>
            <div class="p-5">
                <div id="department-chart" data-labels='@json($departmentData->pluck("name"))'
                    data-series='@json($departmentData->pluck("documents_count"))'>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Documents Out & Return</h5>
            <div class="p-5">
                <div id="docs-out-return-chart" data-labels='@json($outReturnLabels)'
                    data-series='@json($outReturnSeries)'>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1 bg-white rounded-lg border border-gray-200 shadow-sm">
            <h5 class="font-semibold text-gray-800 border-b border-gray-200 py-4 px-5">Last Updated Documents</h5>
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left">
                            <tr>
                                <th class="p-2 font-semibold">Document Name</th>
                                <th class="p-2 font-semibold">Type</th>
                                <th class="p-2 font-semibold">Upload At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestDocuments as $doc)
                            <tr class="border-b border-gray-200">
                                <td class="p-2">{{ $doc->name_id }}</td>
                                <td class="p-2">{{ $doc->documentType->name ?? '-' }}</td>
                                <td class="p-2">{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>