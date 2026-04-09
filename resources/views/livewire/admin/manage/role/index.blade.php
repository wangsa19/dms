<div>
    {{-- Breadcrumbs --}}
    <div class-="text-sm text-gray-500 mb-4">Manage > Role</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">ROLE</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Role</h5>
            <button wire:click="openModal"
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
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Role Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Permissions</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 text-gray-700">
                        @forelse ($roles as $index => $role)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">{{ $roles->firstItem() + $index }}</td>
                            <td class="p-3 font-medium">{{ $role->name }}</td>
                            <td class="p-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($role->permissions->take(10) as $permission)
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ $permission->name }}
                                    </span>
                                    @empty
                                    <span class="text-gray-400 text-xs">- No permissions -</span>
                                    @endforelse
                                    @if($role->permissions->count() > 10)
                                    <span
                                        class="bg-gray-100 text-gray-800 dark:bg-gray-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        +{{ $role->permissions->count() - 10 }} more
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3 flex gap-3">
                                <button wire:click="edit({{ $role->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium transition cursor-pointer hover:underline">
                                    Edit
                                </button>
                                @if ($role->name != 'super_admin')
                                <button wire:click="confirmDelete({{ $role->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium transition cursor-pointer hover:underline">
                                    Delete
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center p-4 text-gray-500">No roles found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table Pagination --}}
            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREATE / EDIT ROLE --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 animate-fadeIn">
        <div class="bg-white w-full max-w-lg rounded-xl p-6 shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 mb-6">
                {{ $roleId ? 'Edit Role' : 'Create Role' }}
            </h3>

            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Role Name</label>
                    <input wire:model="name" placeholder="Enter role name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500"
                        type="text">
                    @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Permissions --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Permissions</label>
                    <div class="border border-gray-200 rounded-lg p-3 mt-1 max-h-60 overflow-y-auto">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($allPermissions as $permission)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="text-sm text-gray-600">{{ $permission->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @error('selectedPermissions')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Modal Buttons --}}
            <div class="flex justify-end gap-3 mt-8">
                <button wire:click="closeModal"
                    class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition">
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
                Are you sure you want to delete this role?
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