<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Documents</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">DOCUMENTS</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Documents</h5>
            @can('create documents')

            <button wire:click="create"
                class="cursor-pointer bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
                + Create New
            </button>
            @endcan
        </div>

        <div class="p-5">
            {{-- Table Controls --}}
            <div class="flex justify-between items-center mb-4 flex-wrap gap-y-4">
                <div>
                    <label class="text-sm text-gray-700">Show
                        <select wire:model.live="perPage"
                            class="border border-gray-300 rounded-md text-sm py-1.5 pl-2 pr-8 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
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
                            placeholder="Search document name..."
                            class="border border-gray-300 rounded-md text-sm p-1.5 ml-2 focus:border-blue-500 focus:ring-blue-500">
                    </label>
                </div>
            </div>

            {{-- Admin Table View --}}
            @if(auth()->user()->hasRole('Admin'))
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">No</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name (ID)</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name (JP)</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Type</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Status</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-700 text-gray-700">
                        @forelse ($documents as $index => $doc)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 align-middle whitespace-nowrap">{{ $documents->firstItem() + $index }}</td>
                            <td class="p-3 align-middle whitespace-nowrap font-medium">{{ $doc->name_id }}</td>
                            <td class="p-3 align-middle whitespace-nowrap font-medium">{{ $doc->name_jp }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{ $doc->documentType->name ?? '-' }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $doc->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $doc->status }}
                                </span>
                            </td>
                            <td class="p-3 align-middle whitespace-nowrap flex gap-3">
                                <a wire:navigate href="{{ route('documents.show', $doc->id) }}"
                                    class="text-emerald-600 hover:text-emerald-800 text-sm font-medium transition hover:underline">View</a>
                                @can('update', $doc)
                                <button wire:click="edit({{ $doc->id }})"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition hover:underline">Edit</button>
                                @endcan
                                @can('delete', $doc)
                                <button wire:click="confirmDelete({{ $doc->id }})"
                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium transition hover:underline">Delete</button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-gray-500">No documents found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            {{-- Non-Admin Card Grid View --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($documents as $doc)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col h-full dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-5 flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 leading-tight">{{ $doc->name_id }}</h3>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full shrink-0 ml-2 {{ $doc->status == 'Active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $doc->status }}
                            </span>
                        </div>
                        
                        @if($doc->name_jp)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $doc->name_jp }}</p>
                        @endif

                        <div class="space-y-2 mt-4">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span class="font-medium text-gray-800 dark:text-gray-200 mr-1">Type:</span> {{ $doc->documentType->name ?? '-' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <span class="font-medium text-gray-800 dark:text-gray-200 mr-1">Dept:</span> {{ $doc->department->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 rounded-b-xl flex gap-3 justify-end items-center">
                        <a wire:navigate href="{{ route('documents.show', $doc->id) }}"
                            class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold transition hover:underline">View</a>
                        
                        @can('update', $doc)
                        <button wire:click="edit({{ $doc->id }})"
                            class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition hover:underline">Edit</button>
                        @endcan
                        
                        @can('delete', $doc)
                        <button wire:click="confirmDelete({{ $doc->id }})"
                            class="text-red-600 hover:text-red-800 text-sm font-semibold transition hover:underline">Delete</button>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p>No documents found.</p>
                </div>
                @endforelse
            </div>
            @endif

            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREATE / EDIT --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg flex flex-col max-h-[90vh]">

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">{{ $documentId ? 'Edit Document' : 'Create New Document' }}
                </h3>
            </div>

            <div class="p-6 overflow-y-auto">
                <form wire:submit.prevent="save" id="documentForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Document Names (ID & JP) --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Dokumen (Indonesia)</label>
                            <input wire:model="name_id" type="text" placeholder="Masukkan nama dokumen"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('name_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">書類名 (Jepang)</label>
                            <input wire:model="name_jp" type="text" placeholder="書類名を入力してください"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('name_jp') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Metadata classifications --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Document Type</label>
                            <select wire:model="document_type_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                <option value="">Select Type...</option>
                                @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('document_type_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Category</label>
                            <select wire:model="category_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                <option value="">Select Category...</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Field</label>
                            <select wire:model="field_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                <option value="">Select Field...</option>
                                @foreach($fields as $field)
                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                            @error('field_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Archived">Archived</option>
                            </select>
                            @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Ownership --}}
                        <div class="col-span-2 border-t pt-4 mt-2">
                            <h4 class="text-sm font-bold text-gray-800 mb-4">Ownership</h4>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Department</label>
                            <select wire:model.live="department_id" @disabled(!auth()->user()->hasRole('Admin'))
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400 disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-700">
                                <option value="">Select Department...</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Section</label>
                            <select wire:model="section_id" @disabled(!$department_id)
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400 disabled:opacity-60">
                                <option value="">Select Section...</option>
                                @foreach($sections as $sec)
                                <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                            @error('section_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Document Owner / PIC</label>
                            <select wire:model="owner_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                <option value="">Select PIC...</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} {{ $emp->user ? '(' . $emp->user->email . ')' : '' }}</option>
                                @endforeach
                            </select>
                            @error('owner_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="rack_id" class="text-sm font-medium text-gray-700">Storage Location
                                (Rack)</label>
                            <select id="rack_id" wire:model="rack_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                <option value="">Select Rack</option>
                                {{-- <option value="">-- No Rack (Digital Only) --</option> --}}
                                @foreach($racks as $rack)
                                <option value="{{ $rack->id }}">
                                    {{ $rack->code }} - {{ $rack->name }} (Col: {{ $rack->column }}, Row: {{ $rack->row
                                    }})
                                </option>
                                @endforeach
                            </select>
                            @error('rack_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if(!$documentId)
                        {{-- File Upload & Versioning --}}
                        <div class="col-span-2 border-t pt-4 mt-2">
                            <h4 class="text-sm font-bold text-gray-800 mb-4">Upload File (First Version)</h4>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Upload File</label>
                            <input wire:model="file" type="file"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">

                            {{-- Loading indicator khusus saat upload --}}
                            <div wire:loading wire:target="file" class="text-xs text-blue-600 mt-1 font-medium">
                                Mengunggah file... Harap tunggu.
                            </div>

                            @error('file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC/X, XLS/X, JPG, PNG (Max: 10MB)</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Catatan Revisi (Opsional)</label>
                            <input wire:model="revision_notes" type="text"
                                placeholder="Misal: Dokumen asli"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400 placeholder-gray-400 dark:placeholder-gray-500">
                            @error('revision_notes') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        @else
                        <div class="col-span-2 border-t pt-4 mt-2">
                            <p class="text-sm text-gray-500 italic">Untuk mengunggah versi baru dari dokumen ini, silakan buka halaman <span class="font-semibold text-blue-600">View (Detail)</span>.</p>
                        </div>
                        @endif

                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition">Cancel</button>
                <button type="submit" form="documentForm" wire:loading.attr="disabled" wire:target="save"
                    class="px-4 py-2 text-sm bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg transition shadow disabled:opacity-50 disabled:cursor-not-allowed">
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
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this document? This action cannot be
                undone.</p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg text-sm">Cancel</button>
                <button wire:click="delete"
                    class="px-4 py-2 bg-red-600 dark:bg-red-500 text-white hover:bg-red-700 dark:hover:bg-red-600 rounded-lg text-sm shadow">Yes,
                    Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>