<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Rack</div>

    <h1 class="text-3xl font-bold text-gray-800 mb-6">RACK</h1>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Rack</h5>
            <button wire:click="create"
                class="cursor-pointer bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-blue-700 transition">
                + Create New
            </button>
        </div>

        <div class="p-5">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-y-4">
                <div>
                    <label class="text-sm text-gray-700">Show
                        <select wire:model.live="perPage" class="border border-gray-300 rounded-md text-sm py-1.5 pl-2 pr-8 focus:border-blue-500">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        entries
                    </label>
                </div>
                <div>
                    <label class="text-sm text-gray-700">Search:
                        <input wire:model.live.debounce.300ms="search" type="search" class="border border-gray-300 rounded-md text-sm p-1.5 ml-2 focus:border-blue-500">
                    </label>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 text-left font-semibold">No</th>
                            <th class="p-3 text-left font-semibold">Name</th>
                            <th class="p-3 text-left font-semibold">Code</th>
                            <th class="p-3 text-left font-semibold">Column</th>
                            <th class="p-3 text-left font-semibold">Row</th>
                            <th class="p-3 text-left font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($racks as $index => $rack)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">{{ $racks->firstItem() + $index }}</td>
                            <td class="p-3 font-medium">{{ $rack->name }}</td>
                            <td class="p-3">{{ $rack->code }}</td>
                            <td class="p-3">{{ $rack->column }}</td>
                            <td class="p-3">{{ $rack->row }}</td>
                            <td class="p-3 flex gap-3">
                                <button wire:click="edit({{ $rack->id }})" class="text-blue-600 hover:underline">Edit</button>
                                <button wire:click="confirmDelete({{ $rack->id }})" class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 text-gray-500">No racks found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $racks->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREATE / EDIT --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg rounded-xl p-6 shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 mb-6">
                {{ $rackId ? 'Edit Rack' : 'Create New Rack' }}
            </h3>

            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Code</label>
                        <input wire:model="code" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        @error('code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Name</label>
                        <input wire:model="name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Column</label>
                            <input wire:model="column" type="text" placeholder="Misal: A" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('column') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Row</label>
                            <input wire:model="row" type="text" placeholder="Misal: 1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('row') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg transition cursor-pointer dark:text-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg shadow">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- DELETE MODAL --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white max-w-sm w-full p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Delete Confirmation</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this rack? This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Cancel</button>
                <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm shadow">Yes, Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>