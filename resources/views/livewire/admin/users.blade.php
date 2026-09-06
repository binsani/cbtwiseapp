<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Users</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ number_format($totalRegisteredUsers) }} registered users &bull; <span class="text-emerald-600 font-bold">{{ number_format($premiumUsersCount) }} Premium</span></p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-red-50 border border-red-100 text-red-800 text-sm font-semibold rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search Bar & Filters -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="relative w-full sm:max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, email or ID..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400">Filter Role:</span>
                <select wire:model.live="roleFilter" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold bg-white text-slate-700 focus:border-emerald-500">
                    <option value="all">All Roles</option>
                    <option value="user">Student / User</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Target Exam</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $usr)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-800">{{ $usr->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $usr->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($usr->plan === 'premium' || $usr->is_premium)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            Premium
                                        </span>
                                        @if($usr->premium_expires_at)
                                            <p class="text-[10px] text-slate-400 mt-0.5 font-medium">Exp: {{ \Carbon\Carbon::parse($usr->premium_expires_at)->format('M d, Y') }}</p>
                                        @endif
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                            Free
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $usr->phone ?: '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $usr->exam_year ? 'UTME ' . $usr->exam_year : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $primaryRole = $usr->roles->first()?->name ?? 'user';
                                        $roleBadgeClass = $primaryRole === 'admin' ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $roleBadgeClass }}">
                                        {{ $primaryRole }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $usr->created_at->format('n/j/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Role Button -->
                                        <button wire:click="openEditRoleModal({{ $usr->id }})" class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Manage Role">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        </button>

                                        <!-- Edit Plan Button -->
                                        <button wire:click="openEditPlanModal({{ $usr->id }})" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Manage Plan / Subscription">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </button>

                                        @if($usr->id !== auth()->id())
                                            <!-- Delete User Button -->
                                            <button wire:click="deleteUser({{ $usr->id }})" wire:confirm="Are you sure you want to permanently delete user '{{ $usr->name }}'?" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-400">
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

        <!-- Role Management Modal -->
        @if ($isRoleModalOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="role-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isRoleModalOpen', false)"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                                <h3 class="text-lg font-bold text-slate-950 font-heading" id="role-modal-title">
                                    Update User Role
                                </h3>
                                <button wire:click="$set('isRoleModalOpen', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form wire:submit.prevent="saveRole" class="space-y-4 mt-4 text-slate-700">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">User Name</label>
                                    <input type="text" readonly value="{{ $editingUserName }}" class="w-full px-3.5 py-2.5 border border-slate-100 bg-slate-50 text-slate-600 rounded-xl text-sm focus:outline-none" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Selected Role *</label>
                                    <select wire:model="selectedRole" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        @foreach($allRoles as $role)
                                            <option value="{{ $role }}">{{ strtoupper($role) }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedRole') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="$set('isRoleModalOpen', false)" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                        Update Role
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Plan / Subscription Management Modal -->
        @if ($isPlanModalOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="plan-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isPlanModalOpen', false)"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                                <h3 class="text-lg font-bold text-slate-950 font-heading" id="plan-modal-title">
                                    Manage Subscription Plan
                                </h3>
                                <button wire:click="$set('isPlanModalOpen', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form wire:submit.prevent="savePlan" class="space-y-4 mt-4 text-slate-700">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">User Name</label>
                                    <input type="text" readonly value="{{ $editingUserName }}" class="w-full px-3.5 py-2.5 border border-slate-100 bg-slate-50 text-slate-600 rounded-xl text-sm focus:outline-none" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Plan Tier *</label>
                                    <select wire:model.live="selectedPlan" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="free">Free Tier</option>
                                        <option value="premium">Premium Pass</option>
                                    </select>
                                </div>

                                @if($selectedPlan === 'premium')
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Access Duration (Days)</label>
                                        <select wire:model="planDurationDays" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="7">7 Days (1 Week Trial)</option>
                                            <option value="30">30 Days (1 Month)</option>
                                            <option value="90">90 Days (3 Months)</option>
                                            <option value="180">180 Days (6 Months)</option>
                                            <option value="365">365 Days (1 Full Year)</option>
                                        </select>
                                    </div>
                                @endif

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="$set('isPlanModalOpen', false)" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                        Save Plan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
    </main>
</div>
