<?php

namespace App\Livewire\Dashboard;

use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserNotifications extends Component
{
    public function markAsRead($id)
    {
        UserNotification::where('user_id', Auth::id())
            ->where('id', $id)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead()
    {
        UserNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $notifications = UserNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.user-notifications', compact('notifications'))
            ->layout('layouts.app');
    }
}
