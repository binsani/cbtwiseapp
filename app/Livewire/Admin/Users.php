<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';

    // Edit Role State
    public $isRoleModalOpen = false;
    public $editingUserId = null;
    public $editingUserName = '';
    public $selectedRole = 'user';
    public $allRoles = [];

    // Edit Plan / Subscription State
    public $isPlanModalOpen = false;
    public $selectedPlan = 'free';
    public $planDurationDays = 30;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->allRoles = Role::pluck('name')->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function openEditRoleModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->editingUserName = $user->name;
        $this->selectedRole = $user->roles->first()?->name ?? 'user';
        $this->isRoleModalOpen = true;
    }

    public function saveRole()
    {
        $this->validate([
            'selectedRole' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($this->editingUserId);
        $user->syncRoles([$this->selectedRole]);

        // Record activity log
        \App\Models\AdminActivityLog::record(
            auth()->id(),
            'user.role_updated',
            'users',
            $user->id,
            ['new_role' => $this->selectedRole]
        );

        session()->flash('message', "User '{$user->name}' roles updated to '{$this->selectedRole}' successfully.");
        $this->isRoleModalOpen = false;
    }

    public function openEditPlanModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->editingUserName = $user->name;
        $this->selectedPlan = $user->plan ?? 'free';
        $this->planDurationDays = 30;
        $this->isPlanModalOpen = true;
    }

    public function savePlan()
    {
        $user = User::findOrFail($this->editingUserId);

        if ($this->selectedPlan === 'premium') {
            $user->update([
                'plan' => 'premium',
                'premium_expires_at' => now()->addDays((int)$this->planDurationDays),
            ]);
            session()->flash('message', "User '{$user->name}' upgraded to Premium for {$this->planDurationDays} days.");
        } else {
            $user->update([
                'plan' => 'free',
                'premium_expires_at' => null,
            ]);
            session()->flash('message', "User '{$user->name}' subscription set to Free tier.");
        }

        $this->isPlanModalOpen = false;
    }

    public function deleteUser($userId)
    {
        if ($userId == auth()->id()) {
            session()->flash('error', 'You cannot delete your own admin account.');
            return;
        }

        $user = User::findOrFail($userId);
        $name = $user->name;
        $user->delete();

        session()->flash('message', "User '{$name}' has been deleted.");
    }

    public function render()
    {
        $query = User::query()->with('roles');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter !== 'all') {
            $query->role($this->roleFilter);
        }

        $users = $query->latest()->paginate(15);
        $totalRegisteredUsers = User::count();
        $premiumUsersCount = User::where('plan', 'premium')->count();

        return view('livewire.admin.users', [
            'users' => $users,
            'totalRegisteredUsers' => $totalRegisteredUsers,
            'premiumUsersCount' => $premiumUsersCount,
        ])->layout('layouts.app');
    }
}
