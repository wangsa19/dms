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
            <div class="flex flex-col md:flex-row gap-4 mb-6">

                <!-- Status Filter -->
                <div class="w-full md:w-48">
                    <select wire:model.live="filterStatus" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="Borrowed">Borrowed</option>
                        <option value="Returned">Returned</option>
                        <option value="Late">Late</option>
                    </select>
                </div>

                <!-- Department Filter -->
                <div class="w-full md:w-48">
                    <select wire:model.live="filterDepartmentId" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Button -->
                <div>
                    <button wire:click="resetFilters" class="w-full md:w-auto px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition whitespace-nowrap">Reset Filter</button>
                </div>
            </div>

            {{-- Admin Table View --}}
            @if(auth()->user()->hasRole('Admin'))
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 select-none">
                        <tr>
                            <th class="p-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('document_outs.created_at')">
                                No.
                                @if($sortField === 'document_outs.created_at')
                                    <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                @endif
                            </th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('documents.name_id')">
                                Document Name
                                @if($sortField === 'documents.name_id')
                                    <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                @endif
                            </th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('borrowers.name')">
                                Borrowed By
                                @if($sortField === 'borrowers.name')
                                    <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                @endif
                            </th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('departments.name')">
                                Department
                                @if($sortField === 'departments.name')
                                    <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                @endif
                            </th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('document_outs.checkout_time')">
                                Checkout Time
                                @if($sortField === 'document_outs.checkout_time')
                                    <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                @endif
                            </th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('document_outs.return_time')">
                                Return Time
                                @if($sortField === 'document_outs.return_time')
                                    <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                @endif
                            </th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('document_outs.status')">
                                Status
                                @if($sortField === 'document_outs.status')
                                    <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                                @endif
                            </th>

                            {{-- Sembunyikan header Action jika tidak punya akses edit ATAU delete --}}
                            @if(auth()->user()->can('edit document outs') || auth()->user()->can('delete document outs'))
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
                            <td class="p-3 align-middle text-sm">{{ $docOut->borrower->department->name ?? 'No Dept' }}</td>
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
                            <td colspan="8" class="text-center p-4 text-gray-500">No documents out found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            {{-- Non-Admin Card Grid View --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($documentOuts as $docOut)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col h-full dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-5 flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 leading-tight">{{ $docOut->document->name_id ?? '-' }}</h3>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full shrink-0 ml-2 {{ $docOut->status == 'Returned' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                {{ $docOut->status }}
                            </span>
                        </div>

                        <div class="space-y-2 mt-4">
                            <div class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div>
                                    <span class="font-medium text-gray-800 dark:text-gray-200 mr-1">Borrower:</span> 
                                    {{ $docOut->borrower->name ?? '-' }}
                                    <div class="text-xs mt-0.5">{{ $docOut->borrower->department->name ?? 'No Dept' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-medium text-gray-800 dark:text-gray-200 mr-1">Checkout:</span> 
                                {{ \Carbon\Carbon::parse($docOut->checkout_time)->format('Y-m-d H:i') }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-medium text-gray-800 dark:text-gray-200 mr-1">Return:</span> 
                                {{ $docOut->return_time ? \Carbon\Carbon::parse($docOut->return_time)->format('Y-m-d H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                    
                    @if((auth()->user()->can('edit document outs') || auth()->user()->can('delete document outs')) && $docOut->created_by === auth()->id())
                    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 rounded-b-xl flex gap-3 justify-end items-center">
                        @can('edit document outs')
                        <button wire:click="edit({{ $docOut->id }})"
                            class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition hover:underline">Edit</button>
                        @endcan
                        
                        @can('delete document outs')
                        <button wire:click="confirmDelete({{ $docOut->id }})"
                            class="text-red-600 hover:text-red-800 text-sm font-semibold transition hover:underline">Delete</button>
                        @endcan
                    </div>
                    @endif
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p>No documents out found.</p>
                </div>
                @endforelse
            </div>
            @endif

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
                        <div class="col-span-full md:col-span-1">
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
                        <div class="col-span-full md:col-span-1">
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
                            <input wire:model.live="checkout_time" type="datetime-local"
                                min="{{ !$documentOutId ? now()->format('Y-m-d\TH:i') : '' }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('checkout_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Return Time --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Return Time</label>
                            <input wire:model="return_time" type="datetime-local"
                                min="{{ $checkout_time ? \Carbon\Carbon::parse($checkout_time)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('return_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-span-full">
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="Borrowed">Borrowed</option>
                                <option value="Returned">Returned</option>
                                <option value="Late">Late</option>
                            </select>
                            @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

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