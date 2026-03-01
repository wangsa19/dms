<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Permission</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">PERMISSION</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Permission</h5>
            {{-- Ganti 'openModal' menjadi 'create' agar lebih semantik, atau biarkan 'openModal' --}}
            <button wire:click="create"
                class="cursor-pointer bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-blue-700 transition">
                + Create New
            </button>
        </div>

        {{-- Card Body --}}
        <div class="p-5">
            {{-- Table Controls --}}
            <div class="flex justify-between items-center mb-4 flex-wrap gap-y-4">
                <div>
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
                    <label class="text-sm text-gray-700">Search:
                        <input wire:model.live.debounce.300ms="search" type="search"
                            class="border border-gray-300 rounded-md text-sm p-1.5 ml-2 focus:border-blue-500 focus:ring-blue-500">
                    </label>
                </div>
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">No</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Guard Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 text-gray-700">
                        @forelse ($permissions as $index => $permission)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">{{ $permissions->firstItem() + $index }}</td>
                            <td class="p-3 font-medium">{{ $permission->name }}</td>
                            <td class="p-3">
                                {{-- Tampilkan guard name sebagai badge --}}
                                <span class="bg-gray-100 text-slate-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $permission->guard_name }}
                                </span>
                            </td>
                            <td class="p-3 flex gap-3">
                                <button wire:click="edit({{ $permission->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium transition cursor-pointer hover:underline">
                                    Edit
                                </button>
                                {{-- Tidak perlu cek super_admin untuk permission --}}
                                <button wire:click="confirmDelete({{ $permission->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium transition cursor-pointer hover:underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center p-4 text-gray-500">No permissions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table Pagination --}}
            <div class="mt-4">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREATE / EDIT PERMISSION --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 animate-fadeIn">
        <div class="bg-white w-full max-w-lg rounded-xl p-6 shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 mb-6">
                {{ $permissionId ? 'Edit Permission' : 'Create Permission' }}
            </h3>

            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Permission Name</label>
                    <input wire:model="name" placeholder="Enter permission name (e.g., post-create)"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500"
                        type="text">
                    @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field untuk 'guard_name' dan 'permissions' tidak diperlukan di sini --}}
                {{-- Kita hardcode guard_name = 'web' saat save --}}

            </div>

            {{-- Modal Buttons --}}
            <div class="flex justify-end gap-3 mt-8">
                <button wire:click="closeModal"
                    class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg transition cursor-pointer">
                    Cancel
                </button>
                <button wire:click="save"
                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition cursor-pointer shadow">
                    Save
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRMATION MODAL --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 animate-fadeIn">
        <div class="bg-white max-w-sm w-full p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Delete Confirmation</h3>
            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to delete this permission?
            </p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm cursor-pointer">
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
</div>