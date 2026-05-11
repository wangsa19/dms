<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Employee</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">EMPLOYEE</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Employee</h5>
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Import Excel --}}
                <button wire:click="openImportModal"
                    class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-green-700 transition-colors">
                    Import Excel
                </button>
                {{-- TOMBOL EXPORT EXCEL --}}
                <button wire:click="export" wire:loading.attr="disabled"
                    class="bg-yellow-500 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-yellow-600 transition-colors flex items-center gap-2">

                    <svg wire:loading wire:target="export" class="animate-spin h-4 w-4 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>

                    Export Excel
                </button>
                {{-- Tombol Create New --}}
                <button wire:click="create"
                    class="cursor-pointer bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-blue-700 transition">
                    + Create New
                </button>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-5">
            {{-- Table Controls --}}
            <div class="flex justify-between items-center mb-4 flex-wrap gap-y-4">
                <div>
                    {{-- Kontrol Show Entries --}}
                    <label class="text-sm text-gray-700">Show
                        <select wire:model.live="perPage"
                            class="border border-gray-300 rounded-md text-sm py-1.5 pl-2 pr-8 focus:border-blue-500 focus:ring-blue-500">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        entries
                    </label>
                </div>

                <div>
                    {{-- Kontrol Search --}}
                    <label class="text-sm text-gray-700">Search:
                        <input wire:model.live.debounce.300ms="search" type="search"
                            class="border border-gray-300 rounded-md text-sm p-1.5 ml-2 focus:border-blue-500 focus:ring-blue-500">
                    </label>
                </div>
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto scrollbar-slim">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">No</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">NIK</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Gender</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Phone</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Department</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Section</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Position</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-700 text-gray-700">
                        {{-- Loop data dari komponen --}}
                        @forelse ($employees as $index => $employee)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 align-middle whitespace-nowrap">{{ $employees->firstItem() + $index }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{ $employee->nik }}</td>
                            <td class="p-3 align-middle whitespace-nowrap font-medium">{{ $employee->name }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{ $employee->gender }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{ $employee->phone ?? '-' }}</td>
                            {{-- Relasi --}}
                            <td class="p-3 align-middle whitespace-nowrap">{{ $employee->department->name ?? '-' }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{ $employee->section->name ?? '-' }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{ $employee->position->name ?? '-' }}</td>

                            <td class="p-3 align-middle whitespace-nowrap flex gap-3">
                                {{-- Tombol Edit --}}
                                <button wire:click="edit({{ $employee->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium transition cursor-pointer hover:underline">
                                    Edit
                                </button>
                                {{-- Tombol Delete --}}
                                <button wire:click="confirmDelete({{ $employee->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium transition cursor-pointer hover:underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        {{-- Tampilan jika data kosong --}}
                        <tr>
                            <td colspan="9" class="text-center p-4 text-gray-500">No employees found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $employees->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREATE / EDIT EMPLOYEE --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 ">
        <div class="bg-white w-full max-w-2xl rounded-xl p-6 shadow-lg max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-800 mb-6">
                {{ $employeeId ? 'Edit Employee' : 'Create New Employee' }}
            </h3>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NIK --}}
                    <div>
                        <label for="nik" class="text-sm font-medium text-gray-700">NIK</label>
                        <input id="nik" wire:model="nik" type="text" placeholder="Enter NIK"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                        @error('nik') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="text-sm font-medium text-gray-700">Name</label>
                        <input id="name" wire:model="name" type="text" placeholder="Enter full name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label for="gender" class="text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" wire:model="gender"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Select Gender</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('gender') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
                        <input id="phone" wire:model="phone" type="text" placeholder="Enter phone number"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                        @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Department --}}
                    <div>
                        <label for="department_id" class="text-sm font-medium text-gray-700">Department</label>
                        <select id="department_id" wire:model.live="department_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Section (Dependent Dropdown) --}}
                    <div>
                        <label for="section_id" class="text-sm font-medium text-gray-700">Section</label>
                        <select id="section_id" wire:model="section_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 bg-white"
                            @if(empty($sections)) disabled @endif>
                            <option value="">{{ empty($sections) ? 'Select Department First' : 'Select Section' }}
                            </option>
                            @foreach($sections as $sect)
                            <option value="{{ $sect->id }}">{{ $sect->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Section (Dependent Dropdown) --}}
                    <div>
                        <label for="section_id" class="text-sm font-medium text-gray-700">Section</label>
                        <select id="section_id" wire:model="section_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 bg-white"
                            @if(empty($sections)) disabled @endif>
                            <option value="">{{ empty($sections) ? 'Select Department First' : 'Select Section' }}
                            </option>
                            @foreach($sections as $sect)
                            <option value="{{ $sect->id }}">{{ $sect->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Position --}}
                    <div>
                        <label for="position_id" class="text-sm font-medium text-gray-700">Position</label>
                        <select id="position_id" wire:model="position_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Select Position</option>
                            @foreach($positions as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                            @endforeach
                        </select>
                        @error('position_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- SEPARATOR UNTUK AKUN SISTEM --}}
                    <div class="md:col-span-2 border-t border-gray-200 pt-6 mt-2">
                        <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-1">System Account
                            (Optional)</h4>
                        <p class="text-xs text-gray-500 mb-4">Fill in the email and role to automatically create a login
                            account for this employee.</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                        <input id="email" wire:model="email" type="email"
                            placeholder="Leave blank if no system access needed"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role_name" class="text-sm font-medium text-gray-700">System Role</label>
                        <select id="role_name" wire:model="role_name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div class="md:col-span-2">
                        <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                        <input id="password" wire:model="password" type="password"
                            placeholder="{{ $userId ? 'Leave blank to keep current password' : 'Min. 6 characters (Required if Email is filled)' }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                        @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div> {{-- Penutup Grid Cols 2 --}}

                {{-- Modal Buttons --}}
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg transition cursor-pointer dark:text-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition cursor-pointer shadow">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRMATION MODAL --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 ">
        <div class="bg-white max-w-sm w-full p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Delete Confirmation</h3>
            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to delete employee <span class="font-bold">{{ $employeeNameToDelete }}</span>?
                This action cannot be undone.
            </p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm cursor-pointer dark:text-gray-700">
                    Cancel
                </button>
                <button wire:click="delete"
                    class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-sm cursor-pointer shadow">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL IMPORT EXCEL --}}
    @if($isImportModalOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Import Employee Data</h3>

            <form wire:submit.prevent="importExcel">
                <div class="space-y-4">

                    {{-- File Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Excel File (.xlsx,
                            .csv)</label>

                        <input type="file" wire:model="fileImport" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-lg" />
                        @error('fileImport') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                        <div wire:loading wire:target="fileImport" class="text-xs text-blue-600 mt-2 font-medium">
                            Uploading file... please wait.
                        </div>
                    </div>

                    {{-- Info Format Excel --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Excel Header Columns (Row 1): <br>
                                    <code
                                        class="font-bold">nik, name, gender, phone, department_id, section_id, position_id</code>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Modal Buttons --}}
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="closeImportModal"
                        class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg transition cursor-pointer text-gray-700">
                        Cancel
                    </button>

                    <button type="submit" wire:loading.attr="disabled" wire:target="fileImport, importExcel"
                        class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg transition cursor-pointer shadow flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">

                        <span wire:loading.remove wire:target="importExcel">Import</span>
                        <span wire:loading wire:target="importExcel">Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>