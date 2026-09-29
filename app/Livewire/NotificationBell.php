<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public bool $isOpen = false;

    // Listen for Livewire events from other components
    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function toggleDropdown(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function markRead(int $id): void
    {
        $notif = SystemNotification::where('user_id', Auth::id())->find($id);
        if ($notif) {
            $notif->markAsRead();
        }
    }

    public function markAllRead(): void
    {
        SystemNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $notifications = SystemNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
        ]);
    }
}
