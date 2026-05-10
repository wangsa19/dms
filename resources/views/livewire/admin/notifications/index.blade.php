<div>
    <div class="text-sm text-gray-500 mb-4">Dashboard > Notifications</div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">NOTIFICATIONS</h1>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h5 class="font-semibold text-lg text-gray-800">All Notifications</h5>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notif)
            <div
                class="px-5 py-4 flex items-center justify-between {{ $notif->status === 'unread' ? 'bg-blue-50/50' : 'bg-white' }}">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $notif->message }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>

                @if($notif->status === 'unread')
                <button wire:click="markAsRead({{ $notif->id }})"
                    class="text-xs text-blue-600 font-semibold hover:underline cursor-pointer">
                    Mark as read
                </button>
                @else
                <span class="text-xs text-gray-400 italic">Read</span>
                @endif
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">No notifications found.</div>
            @endforelse
        </div>
    </div>
</div>