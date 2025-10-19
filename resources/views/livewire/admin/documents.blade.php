<div>
    <div class="text-sm text-gray-500 mb-4">Manage > Document</div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">DOCUMENT</h1>

    {{-- Filter Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
            <h5 class="font-semibold text-lg text-gray-800">Manage Document</h5>
            <a href="#"
                class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">Create
                New</a>
        </div>
        <div class="p-5">
            {{-- STRUKTUR FILTER DIPERBAIKI DENGAN FLEXBOX --}}
            <div class="flex justify-between items-center flex-wrap gap-4">
                {{-- Grup untuk Select Input --}}
                <div class="flex items-center gap-4 flex-grow flex-wrap">
                    <select
                        class="w-full md:w-auto h-10 flex-grow border border-gray-200 ps-2 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option>Select Document Type</option>
                    </select>
                    <select
                        class="w-full md:w-auto h-10 flex-grow border border-gray-200 ps-2 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option>Select Category</option>
                    </select>
                    <select
                        class="w-full md:w-auto h-10 flex-grow border border-gray-200 ps-2 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option>Select Department</option>
                    </select>
                    <select
                        class="w-full md:w-auto h-10 flex-grow border border-gray-200 ps-2 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option>Select Section</option>
                    </select>
                </div>
                {{-- Grup untuk Tombol --}}
                <div class="flex items-center gap-2">
                    <button
                        class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">Filter</button>
                    <button
                        class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-md text-sm hover:bg-gray-300 transition-colors">Clear</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mt-6">
        <div class="p-5">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-y-4">
                <div>
                    <label class="text-sm text-gray-700">Show
                        <select class="border border-gray-200 rounded-md shadow-sm text-sm py-1.5 pl-1 pr-8">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        entries
                    </label>
                </div>
                <div>
                    <label class="text-sm text-gray-700">Search: <input type="search"
                            class="border-gray-300 rounded-md shadow-sm text-sm p-1.5 ml-2"></label>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">No</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Document Type</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Category</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Location</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Call Number</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Version</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Upload Time</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Department</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Section</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Owner</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 align-middle whitespace-nowrap"><span
                                    class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full inline-flex items-center justify-center font-bold text-xs cursor-pointer mr-2">+</span>
                                1</td>
                            <td class="p-3 align-middle whitespace-nowrap">Coba 2</td>
                            <td class="p-3 align-middle whitespace-nowrap">Notification</td>
                            <td class="p-3 align-middle whitespace-nowrap">Plant Operation</td>
                            <td class="p-3 align-middle whitespace-nowrap">A0202</td>
                            <td class="p-3 align-middle whitespace-nowrap">X123456</td>
                            <td class="p-3 align-middle whitespace-nowrap">1</td>
                            <td class="p-3 align-middle whitespace-nowrap">2025-02-01 22:59:57</td>
                            <td class="p-3 align-middle whitespace-nowrap">ENGINEERING</td>
                            <td class="p-3 align-middle whitespace-nowrap">ACCOUNTING</td>
                            <td class="p-3 align-middle whitespace-nowrap">RINI ARDILLAH</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 align-middle whitespace-nowrap"><span
                                    class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full inline-flex items-center justify-center font-bold text-xs cursor-pointer mr-2">+</span>
                                2</td>
                            <td class="p-3 align-middle whitespace-nowrap">Coba 1</td>
                            <td class="p-3 align-middle whitespace-nowrap">Report</td>
                            <td class="p-3 align-middle whitespace-nowrap">Management of building</td>
                            <td class="p-3 align-middle whitespace-nowrap">A0202</td>
                            <td class="p-3 align-middle whitespace-nowrap">X123456</td>
                            <td class="p-3 align-middle whitespace-nowrap">1</td>
                            <td class="p-3 align-middle whitespace-nowrap">2025-02-01 22:59:57</td>
                            <td class="p-3 align-middle whitespace-nowrap">PGA</td>
                            <td class="p-3 align-middle whitespace-nowrap">GENERAL AFFAIR</td>
                            <td class="p-3 align-middle whitespace-nowrap">RINI ARDILLAH</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 align-middle whitespace-nowrap"><span
                                    class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full inline-flex items-center justify-center font-bold text-xs cursor-pointer mr-2">+</span>
                                3</td>
                            <td class="p-3 align-middle whitespace-nowrap">Akta Notaris Tentang Pendirian Perseroan</td>
                            <td class="p-3 align-middle whitespace-nowrap">License</td>
                            <td class="p-3 align-middle whitespace-nowrap">Company/Operation certification</td>
                            <td class="p-3 align-middle whitespace-nowrap">A0101</td>
                            <td class="p-3 align-middle whitespace-nowrap">A33.527-4</td>
                            <td class="p-3 align-middle whitespace-nowrap">3</td>
                            <td class="p-3 align-middle whitespace-nowrap">2025-01-26 02:59:40</td>
                            <td class="p-3 align-middle whitespace-nowrap">ENGINEERING</td>
                            <td class="p-3 align-middle whitespace-nowrap">ACCOUNTING</td>
                            <td class="p-3 align-middle whitespace-nowrap">FLY SUVIANTI</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mt-4 text-sm">
                <div class="text-gray-600">
                    Showing 1 to 3 of 3 entries
                </div>
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <a href="#"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 text-sm font-medium">Previous</a>
                    <a href="#"
                        class="px-4 py-2 border-t border-b border-gray-300 bg-blue-600 text-white z-10 text-sm font-bold">1</a>
                    <a href="#"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 text-sm font-medium">Next</a>
                </div>
            </div>
        </div>
    </div>
</div>