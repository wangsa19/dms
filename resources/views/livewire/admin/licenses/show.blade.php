<div>
    <div class="text-sm text-gray-500 mb-4">
        <a href="/dashboard" class="hover:underline">Dashboard</a> >
        <a href="/licenses" class="hover:underline">Licenses</a> >
        Detail
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">LICENSE DETAIL</h1>
        <a wire:navigate href="/licenses"
            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg text-sm font-medium transition">
            &larr; Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">License Information</h3>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500 font-medium">Nama Lisensi (Indonesia)</p>
                        <p class="font-semibold text-gray-800">{{ $license->name_id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">書類名 (Jepang)</p>
                        <p class="font-semibold text-gray-800">{{ $license->name_jp }}</p>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <p class="text-gray-500 font-medium mb-1">Valid Period</p>
                        <p class="font-bold text-blue-700">
                            {{ \Carbon\Carbon::parse($license->start_date)->format('d M Y') }}
                            &nbsp;&rarr;&nbsp;
                            {{ \Carbon\Carbon::parse($license->end_date)->format('d M Y') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 font-medium">Document Type</p>
                            <p class="font-semibold text-gray-800">{{ $license->documentType->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Category</p>
                            <p class="font-semibold text-gray-800">{{ $license->category->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Field</p>
                            <p class="font-semibold text-gray-800">{{ $license->field->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Status</p>
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $license->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $license->status }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <p class="text-gray-500 font-medium">Occurrence Type</p>
                        <p class="font-semibold text-gray-800">{{ $license->occurrence_type ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Action Frequency</p>
                        <p class="font-semibold text-gray-800">
                            {{ $license->action_frequency_value ?? '-' }}
                            {{ $license->actionFrequencyUnit->name ?? '' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Government Issuer</p>
                        <p class="font-semibold text-gray-800">{{ $license->government_issuer ?? '-' }}</p>
                    </div>

                    <hr>

                    <div>
                        <p class="text-gray-500 font-medium">Department & Section</p>
                        <p class="font-semibold text-gray-800">{{ $license->department->name ?? '-' }} / {{
                            $license->section->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">License Owner (PIC)</p>
                        <p class="font-semibold text-gray-800">{{ $license->owner->name ?? '-' }}</p>
                        <p class="text-sm text-blue-600 font-medium">
                            <a href="mailto:{{ $license->owner->user->email ?? '' }}">
                                {{ $license->owner->user->email ?? 'Email tidak tersedia' }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium text-xs uppercase tracking-wider">Physical Storage</p>
                        <p class="font-semibold text-gray-800">
                            @if($license->rack)
                            <span class="text-blue-600">{{ $license->rack->code }}</span> - {{ $license->rack->name }}
                            <br>
                            <span class="text-xs text-gray-500">Column: {{ $license->rack->column }}, Row: {{
                                $license->rack->row }}</span>
                            @else
                            <span class="text-gray-400 italic">No physical location</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Version History & Files</h3>
                </div>

                @can('update', $license)
                <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4 dark:bg-gray-800/50 dark:border-gray-700">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-3">Upload New Version</h4>
                    <form wire:submit.prevent="uploadVersion" class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                        <div class="flex-grow w-full">
                            <label class="text-xs font-medium text-gray-600 block mb-1">Select File</label>
                            <input wire:model="newFile" type="file" class="w-full text-sm border border-gray-300 rounded bg-white py-1.5 px-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                            @error('newFile') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex-grow w-full">
                            <label class="text-xs font-medium text-gray-600 block mb-1">Revision Notes (Optional)</label>
                            <input wire:model="newRevisionNotes" type="text" placeholder="e.g. Updated license document" class="w-full text-sm border border-gray-300 rounded bg-white py-1.5 px-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500">
                            @error('newRevisionNotes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="shrink-0 w-full sm:w-auto">
                            <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2 px-4 rounded shadow transition disabled:opacity-50">
                                <span wire:loading.remove wire:target="uploadVersion">Upload</span>
                                <span wire:loading wire:target="uploadVersion">Uploading...</span>
                            </button>
                        </div>
                    </form>
                    <div wire:loading wire:target="newFile" class="text-xs text-blue-600 mt-2 font-medium">
                        Uploading file to server... please wait.
                    </div>
                </div>
                @endcan

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="p-3 text-left font-semibold">Ver</th>
                                <th class="p-3 text-left font-semibold">File Name</th>
                                <th class="p-3 text-left font-semibold">Uploader</th>
                                <th class="p-3 text-left font-semibold">Notes</th>
                                <th class="p-3 text-left font-semibold">Upload Date</th>
                                <th class="p-3 text-left font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($license->versions as $version)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 align-middle font-bold text-blue-600">v{{ $version->version_number }}
                                </td>
                                <td class="p-3 align-middle font-medium truncate max-w-xs"
                                    title="{{ $version->file_name }}">
                                    {{ $version->file_name }}
                                    <div class="text-xs text-gray-400 uppercase">{{ $version->file_type }} • {{
                                        number_format($version->file_size / 1024, 2) }} KB</div>
                                </td>
                                <td class="p-3 align-middle">
                                    <span class="font-medium text-gray-800">
                                        {{ $version->uploader->name ?? 'System/Unknown' }}
                                    </span>
                                </td>
                                <td class="p-3 align-middle text-gray-600">{{ $version->revision_notes ?? '-' }}</td>
                                <td class="p-3 align-middle text-gray-600">{{ $version->created_at->format('d M Y, H:i')
                                    }}</td>
                                <td class="p-3 align-middle">
                                    <button wire:click="downloadVersion({{ $version->id }})"
                                        class="flex items-center gap-1 bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">No files uploaded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>