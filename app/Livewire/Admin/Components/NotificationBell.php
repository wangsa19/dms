<?php

namespace App\Livewire\Admin\Components;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead($id)
    {
        \App\Models\Notification::find($id)->update(['status' => 'read']);
    }

    public function render()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('status', 'unread') // Pastikan status sesuai di migrasi lo
            ->latest()
            ->get();

        return view('livewire.admin.components.notification-bell', [
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }
}
