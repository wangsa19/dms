<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.notifications.index', [
            'notifications' => \App\Models\Notification::where('user_id', auth()->id())->latest()->get()
        ]);
    }
}
