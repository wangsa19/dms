<div class="relative" x-data="{ open: false }" wire:poll.30s>

    <button @click="open = !open" type="button"
        class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>

        @if($count > 0)
        <span
            class="absolute top-0 right-0 bg-[#d90000] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold animate-bounce">
            {{ $count }}
        </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
        style="display: none;">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <span class="font-bold text-gray-800 text-sm">Notifications</span>
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notif)
            <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b flex items-start gap-3 transition-colors"
                wire:click="markAsReadAndRedirect({{ $notif->id }})">
                <div class="mt-1 bg-red-100 text-red-600 p-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-800">{{ $notif->document_id ? 'Document Return Due' : ($notif->license_id ? 'License Expiring!' : 'Notification') }}</p>
                    <p class="text-[11px] text-gray-500 leading-tight mt-0.5">{{ $notif->message }}</p>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-400 text-xs">No new notifications</div>
            @endforelse
        </div>

        <a href="{{ route('admin.notifications.index') }}"
            class="block py-2 text-center text-blue-600 text-xs font-bold hover:bg-gray-50">
            View All
        </a>
    </div>
</div>