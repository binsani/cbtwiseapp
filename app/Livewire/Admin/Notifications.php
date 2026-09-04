<?php

namespace App\Livewire\Admin;

use App\Models\AdminNotification;
use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = 'all'; // all, code_redeemed, signup, system
    public $readFilter = 'all'; // all, unread, read
    public $dateFrom;
    public $dateTo;

    // Selection properties
    public $selectedNotifications = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
        'readFilter' => ['except' => 'all'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedReadFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedNotifications = AdminNotification::query()
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }

    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->update(['is_read' => true]);
    }

    public function delete($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->delete();
        session()->flash('message', 'Notification deleted successfully.');
    }

    public function markSelectedAsRead()
    {
        AdminNotification::whereIn('id', $this->selectedNotifications)->update(['is_read' => true]);
        $this->selectedNotifications = [];
        $this->selectAll = false;
        session()->flash('message', 'Selected notifications marked as read.');
    }

    public function deleteSelected()
    {
        AdminNotification::whereIn('id', $this->selectedNotifications)->delete();
        $this->selectedNotifications = [];
        $this->selectAll = false;
        session()->flash('message', 'Selected notifications deleted.');
    }

    public function render()
    {
        // Counts for visual pill
        $unreadCount = AdminNotification::where('is_read', false)->count();
        $totalNotifications = AdminNotification::count();

        // Query definition
        $query = AdminNotification::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->readFilter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->readFilter === 'read') {
            $query->where('is_read', true);
        }

        if ($this->dateFrom) {
            $query->where('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->where('created_at', '<=', $this->dateTo);
        }

        $notifications = $query->latest()->paginate(15);

        return view('livewire.admin.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalNotifications' => $totalNotifications,
        ])->layout('layouts.app');
    }
}
