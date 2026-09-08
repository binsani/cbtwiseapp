<?php

namespace App\Livewire\Admin;

use App\Models\AdminActivityLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = 'all';
    public $adminFilter = 'all';

    public $viewingLog = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'actionFilter' => ['except' => 'all'],
        'adminFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingActionFilter()
    {
        $this->resetPage();
    }

    public function updatingAdminFilter()
    {
        $this->resetPage();
    }

    public function viewDetails($id)
    {
        $this->viewingLog = AdminActivityLog::with('admin')->findOrFail($id);
    }

    public function closeDetails()
    {
        $this->viewingLog = null;
    }

    public function render()
    {
        $query = AdminActivityLog::with('admin')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('action', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhere('subject_type', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->actionFilter !== 'all') {
            $query->where('action', $this->actionFilter);
        }

        if ($this->adminFilter !== 'all') {
            $query->where('admin_id', $this->adminFilter);
        }

        $logs = $query->paginate(20);

        $distinctActions = AdminActivityLog::distinct()->pluck('action')->toArray();
        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'moderator', 'support', 'content_editor', 'analyst']);
        })->get();

        return view('livewire.admin.activity-logs', [
            'logs' => $logs,
            'distinctActions' => $distinctActions,
            'admins' => $admins,
        ])->layout('layouts.app');
    }
}
