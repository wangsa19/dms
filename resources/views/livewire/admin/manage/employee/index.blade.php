<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Employee</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">EMPLOYEE</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Employee</h5>
            <div class="flex items-center gap-2">
                <a href="#"
                    class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-md text-sm hover:bg-gray-300 transition-colors">Download
                    Template</a>
                <a href="#"
                    class="bg-green-600 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-green-700 transition-colors">Import
                    Excel</a>
                <a href="#"
                    class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">Create
                    New</a>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-5">
            {{-- Table Controls --}}
            <div class="flex justify-between items-center mb-4 flex-wrap gap-y-4">
                <div>
                    <label class="text-sm text-gray-700">Show
                        <select
                            class="border border-gray-200 rounded-md shadow-sm text-sm py-1.5 pl-2 pr-8 focus:border-blue-500 focus:ring-blue-500">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        entries
                    </label>
                </div>
                <div>
                    <label class="text-sm text-gray-700">Search:
                        <input type="search"
                            class="border border-gray-300 rounded-md shadow-sm text-sm p-1.5 ml-2 focus:border-blue-500 focus:ring-blue-500">
                    </label>
                </div>
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">No</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">NIK</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Gender</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Email</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Department</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Section</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Position</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 align-middle whitespace-nowrap">1</td>
                            <td class="p-3 align-middle whitespace-nowrap">0011395</td>
                            <td class="p-3 align-middle whitespace-nowrap">ELY SUYANTI</td>
                            <td class="p-3 align-middle whitespace-nowrap">Perempuan</td>
                            <td class="p-3 align-middle whitespace-nowrap">coba@gmail.com</td>
                            <td class="p-3 align-middle whitespace-nowrap">ENGINEERING</td>
                            <td class="p-3 align-middle whitespace-nowrap">PRODUCTION ENGINEERING</td>
                            <td class="p-3 align-middle whitespace-nowrap">ADMINISTRASI</td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="w-5 h-5">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 align-middle whitespace-nowrap">2</td>
                            <td class="p-3 align-middle whitespace-nowrap">012458</td>
                            <td class="p-3 align-middle whitespace-nowrap">RINI ARDILLAH</td>
                            <td class="p-3 align-middle whitespace-nowrap">Perempuan</td>
                            <td class="p-3 align-middle whitespace-nowrap">coba@gmail.com</td>
                            <td class="p-3 align-middle whitespace-nowrap">ENGINEERING</td>
                            <td class="p-3 align-middle whitespace-nowrap">PRODUCTION ENGINEERING</td>
                            <td class="p-3 align-middle whitespace-nowrap">ADMINISTRASI</td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="w-5 h-5">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Table Pagination --}}
            <div class="flex justify-between items-center mt-4 text-sm">
                <div class="text-gray-600">
                    Showing 1 to 2 of 2 entries
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