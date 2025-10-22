<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Role</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">ROLE</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Role</h5>
            <a href="#"
                class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">Create
                New</a>
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
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Guard Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Permissions</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 align-middle whitespace-nowrap">1</td>
                            <td class="p-3 align-middle whitespace-nowrap">super_admin</td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">user-create</span>
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">user-edit</span>
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">user-delete</span>
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">role-create</span>
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">role-edit</span>
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">role-delete</span>
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">permission-create</span>
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">permission-edit</span>
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">permission-delete</span>
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">department-create</span>
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">department-edit</span>
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">department-delete</span>
                                </div>
                            </td>
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
                    Showing 1 to 1 of 1 entries
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