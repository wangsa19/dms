<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > License</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">LICENSE</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage License</h5>
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
                            <th class="p-3 text-left font-semibold whitespace-nowrap">No.</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Field</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Category</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name EN</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name ID</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name JP</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Document Type</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Occurrence Type</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action Frequency</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Government</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Start Date</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">End Date</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Reminder Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        {{-- State for No Data --}}
                        <tr>
                            <td colspan="13" class="text-center p-8 text-gray-500">
                                No data available in table
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Table Pagination --}}
            <div class="flex justify-between items-center mt-4 text-sm">
                <div class="text-gray-600">
                    Showing 0 to 0 of 0 entries
                </div>
                {{-- Pagination for empty state --}}
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <a href="#"
                        class="px-4 py-2 text-gray-400 bg-white border border-gray-300 rounded-l-md text-sm font-medium cursor-not-allowed">Previous</a>
                    <a href="#"
                        class="px-4 py-2 text-gray-400 bg-white border-t border-b border-gray-300 text-sm font-medium cursor-not-allowed">Next</a>
                </div>
            </div>
        </div>
    </div>
</div>