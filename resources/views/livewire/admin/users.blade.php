<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">User Management</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ number_format($totalRegisteredUsers) }} registered accounts &bull; 
                    <span class="text-emerald-600 font-bold">{{ number_format($premiumUsersCount) }} Premium</span> &bull;
                    <span class="text-rose-500 font-bold">{{ number_format($suspendedUsersCount) }} Suspended</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="exportUsers" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export CSV</span>
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center justify-between">
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-red-50 border border-red-100 text-red-800 text-sm font-semibold rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <!-- Password Reset Flash Alert -->
        @if ($newTempPassword)
            <div class="p-5 bg-amber-50 border-2 border-amber-300 text-amber-900 rounded-2xl flex items-center justify-between shadow-sm">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-amber-800">Password Reset Succeeded</h4>
                    <p class="text-xs mt-1">A temporary password was generated for <strong>{{ $newTempPassword['name'] }}</strong> ({{ $newTempPassword['email'] }}):</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="font-mono text-sm font-black bg-white px-3 py-1 rounded-lg border border-amber-200 select-all">{{ $newTempPassword['password'] }}</span>
                        <span class="text-[11px] text-amber-700">Please provide this password to the student securely.</span>
                    </div>
                </div>
                <button wire:click="closePasswordAlert" class="text-amber-600 hover:text-amber-800 font-bold text-xs p-1">✕ Close</button>
            </div>
        @endif

        <!-- Search Bar & Filters -->
        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="relative sm:col-span-2">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by student name, email, or ID..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs font-medium transition-colors outline-none" />
                </div>

                <div>
                    <select wire:model.live="roleFilter" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:border-emerald-500 outline-none">
                        <option value="all">All Roles</option>
                        @foreach($allRoles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select wire:model.live="statusFilter" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:border-emerald-500 outline-none">
                        <option value="all">All Statuses</option>
                        <option value="active">Active Accounts</option>
                        <option value="suspended">Suspended Accounts</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Plan</th>
                            <th class="px-6 py-4">Target Exam</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Joined</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($users as $usr)
                            <tr class="hover:bg-slate-50/40 transition-colors {{ $usr->is_suspended ? 'bg-rose-50/30' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $usr->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $usr->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($usr->is_suspended)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-800">
                                            Suspended
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($usr->plan === 'premium')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            ★ Premium
                                        </span>
                                        @if($usr->premium_expires_at)
                                            <p class="text-[10px] text-slate-400 mt-0.5">Exp: {{ $usr->premium_expires_at->format('M d, Y') }}</p>
                                        @endif
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                            Free
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    {{ $usr->exam_year ? 'UTME ' . $usr->exam_year : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $primaryRole = $usr->roles->first()?->name ?? 'user';
                                        $roleBadgeClass = match($primaryRole) {
                                            'admin' => 'bg-red-50 text-red-700',
                                            'moderator' => 'bg-purple-50 text-purple-700',
                                            'support' => 'bg-blue-50 text-blue-700',
                                            'content_editor' => 'bg-amber-50 text-amber-700',
                                            'analyst' => 'bg-teal-50 text-teal-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $roleBadgeClass }}">
                                        {{ $primaryRole }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                    {{ $usr->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Inspect Details Button -->
                                        <button wire:click="inspectUser({{ $usr->id }})" class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="View Student Progress & Attempts">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit Role Button -->
                                        <button wire:click="openEditRoleModal({{ $usr->id }})" class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Change Role">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        </button>

                                        <!-- Edit Plan Button -->
                                        <button wire:click="openEditPlanModal({{ $usr->id }})" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Manage Subscription">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </button>

                                        <!-- Reset Password Button -->
                                        <button wire:click="resetPassword({{ $usr->id }})" wire:confirm="Generate a new temporary password for {{ $usr->name }}?" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Reset Password">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                        </button>

                                        <!-- Suspend / Restore Toggle -->
                                        @if($usr->id !== auth()->id())
                                            @if($usr->is_suspended)
                                                <button wire:click="restoreUser({{ $usr->id }})" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Restore User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </button>
                                            @else
                                                <button wire:click="suspendUser({{ $usr->id }})" wire:confirm="Suspend {{ $usr->name }}? They will not be able to log in." class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Suspend User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400 text-xs">
                                    No users found matching query.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- User Inspection Modal -->
        @if ($isInspectModalOpen && $inspectedUser)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-6 max-w-2xl w-full shadow-2xl space-y-5 max-h-[85vh] overflow-y-auto">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm">
                                {{ strtoupper(substr($inspectedUser->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 font-heading">{{ $inspectedUser->name }}</h3>
                                <p class="text-xs text-slate-400">{{ $inspectedUser->email }} &bull; State: {{ $inspectedUser->state ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <button wire:click="closeInspectModal" class="text-slate-400 hover:text-slate-600 text-sm font-bold">✕</button>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Total Attempts</p>
                            <h4 class="text-xl font-black text-slate-900 mt-1">{{ $inspectedUser->examSessions->count() }}</h4>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Subscription</p>
                            <h4 class="text-xl font-black text-emerald-600 mt-1 capitalize">{{ $inspectedUser->plan }}</h4>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Study Streak</p>
                            <h4 class="text-xl font-black text-slate-900 mt-1">{{ $inspectedUser->study_streak_days }} days</h4>
                        </div>
                    </div>

                    <!-- Recent Exam Attempts -->
                    <div>
                        <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Recent Examination Attempts</h4>
                        <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden">
                            @forelse($inspectedUser->examSessions as $sess)
                                <div class="p-3.5 flex items-center justify-between text-xs hover:bg-slate-50">
                                    <div>
                                        <span class="font-bold text-slate-800">{{ $sess->exam->name ?? 'Exam' }}</span>
                                        <span class="text-[10px] text-slate-400 ml-1.5 uppercase font-semibold">({{ $sess->mode }})</span>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Score: <strong>{{ $sess->score ?? 0 }}%</strong> ({{ $sess->correct_count ?? 0 }}/{{ $sess->total_questions ?? 0 }} correct)</p>
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ $sess->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-xs text-slate-400">
                                    No practice or examination sessions recorded yet.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button wire:click="closeInspectModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Role Management Modal -->
        @if ($isRoleModalOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-950 font-heading">Update User Role</h3>
                        <button wire:click="$set('isRoleModalOpen', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <form wire:submit.prevent="saveRole" class="space-y-4 text-slate-700 text-xs">
                        <div>
                            <label class="block font-bold text-slate-400 uppercase mb-1">User Name</label>
                            <input type="text" readonly value="{{ $editingUserName }}" class="w-full px-3.5 py-2.5 border border-slate-100 bg-slate-50 text-slate-600 rounded-xl focus:outline-none" />
                        </div>

                        <div>
                            <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Selected Role *</label>
                            <select wire:model="selectedRole" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-emerald-500">
                                @foreach($allRoles as $role)
                                    <option value="{{ $role }}">{{ strtoupper($role) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="$set('isRoleModalOpen', false)" class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl font-bold">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-sm">
                                Save Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Plan / Subscription Management Modal -->
        @if ($isPlanModalOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-950 font-heading">Manage Subscription Plan</h3>
                        <button wire:click="$set('isPlanModalOpen', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <form wire:submit.prevent="savePlan" class="space-y-4 text-slate-700 text-xs">
                        <div>
                            <label class="block font-bold text-slate-400 uppercase mb-1">User Name</label>
                            <input type="text" readonly value="{{ $editingUserName }}" class="w-full px-3.5 py-2.5 border border-slate-100 bg-slate-50 text-slate-600 rounded-xl focus:outline-none" />
                        </div>

                        <div>
                            <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Plan Tier *</label>
                            <select wire:model.live="selectedPlan" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-emerald-500">
                                <option value="free">Free Tier</option>
                                <option value="premium">Premium Pass</option>
                            </select>
                        </div>

                        @if($selectedPlan === 'premium')
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Access Duration (Days)</label>
                                <select wire:model="planDurationDays" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-emerald-500">
                                    <option value="7">7 Days (1 Week Trial)</option>
                                    <option value="30">30 Days (1 Month)</option>
                                    <option value="90">90 Days (3 Months)</option>
                                    <option value="180">180 Days (6 Months)</option>
                                    <option value="365">365 Days (1 Full Year)</option>
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="$set('isPlanModalOpen', false)" class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl font-bold">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-sm">
                                Save Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </main>
</div>
