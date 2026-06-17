<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm text-gray-500 mb-4">Manage > Licenses</div>

    {{-- Main Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">LICENSES</h1>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-y-4">
            <h5 class="font-semibold text-lg text-gray-800">Manage Corporate Licenses</h5>
            @can('create licenses')
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
                            placeholder="Search name or issuer..."
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
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name (ID)</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Name (JP)</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Field</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Valid Period</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Status</th>
                            <th class="p-3 text-left font-semibold whitespace-nowrap">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-700 text-gray-700">
                        @forelse ($licenses as $index => $lic)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 align-middle whitespace-nowrap">{{ $licenses->firstItem() + $index }}</td>
                            <td class="p-3 align-middle whitespace-nowrap font-medium">{{ $lic->name_id }}</td>
                            <td class="p-3 align-middle whitespace-nowrap font-medium">{{ $lic->name_jp }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">{{ $lic->field->name ?? '-' }}</td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($lic->start_date)->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($lic->end_date)->format('d M Y') }}
                            </td>
                            <td class="p-3 align-middle whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $lic->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $lic->status }}
                                </span>
                            </td>
                            <td class="p-3 align-middle whitespace-nowrap flex gap-3">
                                <a wire:navigate href="{{ route('licenses.show', $lic->id) }}"
                                    class="text-emerald-600 hover:text-emerald-800 text-sm font-medium transition hover:underline">View</a>

                                @can('update', $lic)
                                <button wire:click="edit({{ $lic->id }})"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition hover:underline">Edit</button>
                                @endcan

                                @can('delete', $lic)
                                <button wire:click="confirmDelete({{ $lic->id }})"
                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium transition hover:underline">Delete</button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-gray-500">No licenses found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $licenses->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREATE / EDIT --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg flex flex-col max-h-[90vh]">

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">{{ $licenseId ? 'Edit License' : 'Create New License' }}
                </h3>
            </div>

            <div class="p-6 overflow-y-auto">
                <form wire:submit.prevent="save" id="licenseForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Multi-language Names --}}
                        <div>
                            {{-- Label Disamakan dengan Documents --}}
                            <label class="text-sm font-medium text-gray-700">Nama Dokumen (Indonesia)</label>
                            <input wire:model="name_id" type="text" placeholder="Masukkan nama dokumen"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('name_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            {{-- Label Disamakan dengan Documents --}}
                            <label class="text-sm font-medium text-gray-700">書類名 (Jepang)</label>
                            <input wire:model="name_jp" type="text" placeholder="書類名を入力してください"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('name_jp') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Metadata classifications --}}
                        <div>
                            {{-- Label Disamakan dengan Documents --}}
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
                            {{-- Label Disamakan dengan Documents --}}
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
                            {{-- Label Disamakan dengan Documents --}}
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

                        {{-- Input khusus Licenses (Tetap dipertahankan) --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">発生タイプ (Occurrence Type)</label>
                            <select wire:model="occurrence_type"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                <option value="">Select Occurrence Type...</option>
                                <option value="Reguler">Reguler (Jangka waktu tetap)</option>
                                <option value="Incident">Pada saat kejadian (Incident)</option>
                            </select>
                            @error('occurrence_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">対応都度 (Action Frequency)</label>
                            <div class="flex gap-2 mt-1">
                                {{-- Input Angka --}}
                                <input wire:model="action_frequency_value" type="number" placeholder="Nilai"
                                    class="w-1/3 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

                                {{-- Dropdown Satuan Unit --}}
                                <select wire:model="action_frequency_unit_id"
                                    class="w-2/3 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
                                    <option value="">Pilih Satuan...</option>
                                    @foreach($actionFrequencyUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('action_frequency_value') <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('action_frequency_unit_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">行政機関 (Government)</label>
                            <input wire:model="government_issuer" type="text" placeholder="Government issuer"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('government_issuer') <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Valid Period --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">開始日 (Start Date)</label>
                            <input wire:model="start_date" type="date"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('start_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">終了日 (End Date)</label>
                            <input wire:model="end_date" type="date"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('end_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Ownership & Location --}}
                        <div class="col-span-2 border-t pt-4 mt-2">
                            <h4 class="text-sm font-bold text-gray-800 mb-4">Ownership</h4>
                        </div>

                        <div>
                            {{-- Label Disamakan dengan Documents --}}
                            <label class="text-sm font-medium text-gray-700">Department</label>
                            <select wire:model.live="department_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400">
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
                            {{-- Label Disamakan dengan Documents --}}
                            <label class="text-sm font-medium text-gray-700">Document Owner / PIC</label>
                            <select wire:model="owner_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400 disabled:opacity-60">
                                <option value="">Select PIC...</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                            @error('owner_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="rack_id" class="text-sm font-medium text-gray-700">Storage Location
                                (Rack)</label>
                            <select id="rack_id" wire:model="rack_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-blue-400 disabled:opacity-60">
                                <option value="">-- No Rack (Digital Only) --</option>
                                @foreach($racks as $rack)
                                <option value="{{ $rack->id }}">
                                    {{ $rack->code }} - {{ $rack->name }} (Col: {{ $rack->column }}, Row: {{ $rack->row
                                    }})
                                </option>
                                @endforeach
                            </select>
                            @error('rack_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- File Upload & Versioning --}}
                        <div class="col-span-2 border-t pt-4 mt-2">
                            <h4 class="text-sm font-bold text-gray-800 mb-4">Upload File & Versioning</h4>
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
                                placeholder="Misal: Update dokumen tahun 2024"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('revision_notes') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition">Cancel</button>
                <button type="submit" form="licenseForm"
                    class="px-4 py-2 text-sm bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg transition shadow">Save</button>
            </div>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRMATION MODAL --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div class="bg-white max-w-sm w-full p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Delete Confirmation</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this license? This action cannot be
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