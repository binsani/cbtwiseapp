<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\ExamSession;
use App\Services\AdminLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';
    public $planFilter = 'all';
    public $statusFilter = 'all'; // all, active, suspended

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

    // Inspect User Detail Modal
    public $inspectedUser = null;
    public $isInspectModalOpen = false;

    // Generated Password Display
    public $newTempPassword = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => 'all'],
        'planFilter' => ['except' => 'all'],
        'statusFilter' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->allRoles = Role::pluck('name')->toArray();
        if (empty($this->allRoles)) {
            $this->allRoles = ['admin', 'moderator', 'support', 'content_editor', 'analyst', 'user'];
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function updatedPlanFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
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
        $oldRole = $user->roles->first()?->name ?? 'user';
        $user->syncRoles([$this->selectedRole]);

        AdminLogger::log(
            'user.role_updated',
            $user,
            ['user_id' => $user->id, 'new_role' => $this->selectedRole],
            ['role' => $oldRole],
            ['role' => $this->selectedRole]
        );

        session()->flash('message', "User '{$user->name}' role updated to '{$this->selectedRole}' successfully.");
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
            AdminLogger::log('user.subscription_upgraded', $user, ['days' => $this->planDurationDays]);
            session()->flash('message', "User '{$user->name}' upgraded to Premium for {$this->planDurationDays} days.");
        } else {
            $user->update([
                'plan' => 'free',
                'premium_expires_at' => null,
            ]);
            AdminLogger::log('user.subscription_downgraded', $user, ['plan' => 'free']);
            session()->flash('message', "User '{$user->name}' subscription set to Free tier.");
        }

        $this->isPlanModalOpen = false;
    }

    public function suspendUser($userId)
    {
        if ($userId == auth()->id()) {
            session()->flash('error', 'You cannot suspend your own administrative account.');
            return;
        }

        $user = User::findOrFail($userId);
        $user->update([
            'is_suspended' => true,
            'suspended_at' => now(),
        ]);

        AdminLogger::log('user.suspended', $user, ['reason' => 'Admin suspension']);
        session()->flash('message', "User '{$user->name}' has been suspended.");
    }

    public function restoreUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'is_suspended' => false,
            'suspended_at' => null,
        ]);

        AdminLogger::log('user.restored', $user);
        session()->flash('message', "User '{$user->name}' account has been restored.");
    }

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);
        $tempPassword = 'CBT' . Str::random(8) . '!';
        
        $user->update([
            'password' => Hash::make($tempPassword),
        ]);

        $this->newTempPassword = [
            'userId' => $userId,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $tempPassword,
        ];

        AdminLogger::log('user.password_reset', $user);
        session()->flash('message', "Temporary password generated for {$user->name}.");
    }

    public function closePasswordAlert()
    {
        $this->newTempPassword = null;
    }

    public function inspectUser($userId)
    {
        $this->inspectedUser = User::with([
            'roles',
            'examSessions' => function($q) {
                $q->with('exam')->latest()->take(10);
            }
        ])->findOrFail($userId);

        $this->isInspectModalOpen = true;
    }

    public function closeInspectModal()
    {
        $this->isInspectModalOpen = false;
        $this->inspectedUser = null;
    }

    public function exportUsers(): StreamedResponse
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=users_export_' . now()->toDateString() . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Plan', 'Status', 'Exam Year', 'State', 'Registered At']);

            User::with('roles')->chunk(100, function($users) use ($file) {
                foreach ($users as $user) {
                    $status = $user->is_suspended ? 'suspended' : 'active';
                    $role = $user->roles->first()?->name ?? 'user';
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $role,
                        $user->plan,
                        $status,
                        $user->exam_year ?? 'N/A',
                        $user->state ?? 'N/A',
                        $user->created_at->toDateTimeString(),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function deleteUser($userId)
    {
        if ($userId == auth()->id()) {
            session()->flash('error', 'You cannot delete your own admin account.');
            return;
        }

        $user = User::findOrFail($userId);
        $name = $user->name;
        
        AdminLogger::log('user.deleted', $user, ['name' => $name, 'email' => $user->email]);
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

        if ($this->planFilter !== 'all') {
            $query->where('plan', $this->planFilter);
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_suspended', false);
        } elseif ($this->statusFilter === 'suspended') {
            $query->where('is_suspended', true);
        }

        $users = $query->latest()->paginate(15);
        $totalRegisteredUsers = User::count();
        $premiumUsersCount = User::where('plan', 'premium')->count();
        $suspendedUsersCount = User::where('is_suspended', true)->count();

        return view('livewire.admin.users', [
            'users' => $users,
            'totalRegisteredUsers' => $totalRegisteredUsers,
            'premiumUsersCount' => $premiumUsersCount,
            'suspendedUsersCount' => $suspendedUsersCount,
        ])->layout('layouts.app');
    }
}
