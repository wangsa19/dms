<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Documents Out</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">DOCUMENTS OUT</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Documents Out</h5>

            {{-- IMPLEMENTASI @can UNTUK TOMBOL CREATE --}}
            @can('create document outs')
            <button wire:click="create"
                class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">
                + Create New
            </button>
            @endcan
        </div>

        {{-- Card Body --}}
        <div class="p-5">
            {{-- Table Controls (Show & Search) --}}
            <div class="flex justify-between items-center mb-4 flex-wrap gap-y-4">
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">No.</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Document Name</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Borrowed By</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Checkout Time</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Return Time</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Status</th>

                            {{-- Sembunyikan header Action jika tidak punya akses edit ATAU delete --}}
                            @if(auth()->user()->can('edit document outs') || auth()->user()->can('delete document
                            outs'))
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($documentOuts as $index => $docOut)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 align-middle whitespace-nowrap">{{ $documentOuts->firstItem() + $index }}
                            </td>
                            <td class="p-3 align-middle font-medium">{{ $docOut->document->name_id ?? '-' }}</td>
                            <td class="p-3 align-middle font-medium">{{ $docOut->borrower->name ?? '-' }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{
                                \Carbon\Carbon::parse($docOut->checkout_time)->format('Y-m-d H:i') }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                {{ $docOut->return_time ? \Carbon\Carbon::parse($docOut->return_time)->format('Y-m-d
                                H:i') : '-' }}
                            </td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $docOut->status == 'Returned' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $docOut->status }}
                                </span>
                            </td>

                            {{-- IMPLEMENTASI @can UNTUK TOMBOL EDIT & DELETE --}}
                            @if(auth()->user()->can('edit document outs') || auth()->user()->can('delete document outs'))
                            <td class="p-3 align-middle whitespace-nowrap flex gap-2">
                                {{-- Hanya tampilkan tombol jika Admin ATAU user pembuat record --}}
                                @if(auth()->user()->hasRole('Admin') || $docOut->created_by === auth()->id())
                                    @can('edit document outs')
                                    <button wire:click="edit({{ $docOut->id }})"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline">Edit</button>
                                    @endcan

                                    @can('delete document outs')
                                    <button wire:click="confirmDelete({{ $docOut->id }})"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium hover:underline">Delete</button>
                                    @endcan
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-gray-500">No documents out found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table Pagination --}}
            <div class="mt-4">
                {{ $documentOuts->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREATE / EDIT --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">{{ $documentOutId ? 'Edit Document Out' : 'Create Document
                    Out' }}</h3>
            </div>

            <div class="p-6 overflow-y-auto">
                <form wire:submit.prevent="save" id="documentOutForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Document --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium text-gray-700">Document</label>
                            <select wire:model="document_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Document...</option>
                                @foreach($documents as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->name_id }} ({{ $doc->documentType->name ?? '-'
                                    }})</option>
                                @endforeach
                            </select>
                            @error('document_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Borrower --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium text-gray-700">Borrower (Employee)</label>
                            <select wire:model="borrower_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Borrower...</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} - NIK: {{ $emp->nik ?? '-' }}</option>
                                @endforeach
                            </select>
                            @error('borrower_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Checkout Time --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Checkout Time</label>
                            <input wire:model="checkout_time" type="datetime-local"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('checkout_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Return Time --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Return Time</label>
                            <input wire:model="return_time" type="datetime-local"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('return_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status (Auto determined) --}}
                        {{-- <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="Borrowed">Borrowed</option>
                                <option value="Returned">Returned</option>
                                <option value="Late">Late</option>
                            </select>
                            @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div> --}}

                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition">Cancel</button>
                <button type="submit" form="documentOutForm" wire:loading.attr="disabled" wire:target="save"
                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">Save</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRMATION MODAL --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div class="bg-white max-w-sm w-full p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Delete Confirmation</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this document out record? This action
                cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)"
                    class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300 rounded-lg text-sm">Cancel</button>
                <button wire:click="delete"
                    class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-sm shadow">Yes,
                    Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>