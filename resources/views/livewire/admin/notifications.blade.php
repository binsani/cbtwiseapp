<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-6 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Sidebar Toggle Mock Icon -->
                <button class="text-slate-700 hover:text-slate-900 transition-colors mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl font-black text-slate-950 font-heading">Notifications</h1>
                    <p class="text-xs text-slate-500 mt-0.5">View and manage all admin notifications</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Sound Option Icon -->
                <button class="text-slate-600 hover:text-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18.75V5.25L7.75 9.5H4.5v5h3.25L12 18.75z"/>
                    </svg>
                </button>

                <!-- Notification Bell Badge -->
                <div class="relative cursor-pointer">
                    <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if ($unreadCount > 0)
                        <span class="absolute -top-1 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </div>

                <!-- Unread count pill matching mockup "28 unread" -->
                <div class="bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-full transition-all">
                    <span class="text-xs font-bold text-slate-800">{{ $unreadCount }} unread</span>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filter and Search Bar -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Search field -->
            <div class="relative md:col-span-5">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search notifications..." class="w-full pl-10 pr-4 py-3 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors bg-white font-medium text-slate-800" />
            </div>

            <!-- Type Selector -->
            <div class="md:col-span-2 relative">
                <select wire:model.live="typeFilter" class="w-full pl-4 pr-10 py-3 border border-slate-200 rounded-2xl text-sm font-medium bg-white text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 appearance-none">
                    <option value="all">All types</option>
                    <option value="code_redeemed">Code Redeemed</option>
                    <option value="signup">New Signup</option>
                    <option value="system">System</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <!-- Read Filter Selector -->
            <div class="md:col-span-1 relative">
                <select wire:model.live="readFilter" class="w-full pl-4 pr-8 py-3 border border-slate-200 rounded-2xl text-sm font-medium bg-white text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 appearance-none">
                    <option value="all">All</option>
                    <option value="unread">Unread</option>
                    <option value="read">Read</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <!-- Date From -->
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <input type="date" wire:model.live="dateFrom" class="w-full pl-9 pr-3 py-3 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 bg-white" placeholder="From" />
            </div>

            <!-- Date To -->
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <input type="date" wire:model.live="dateTo" class="w-full pl-9 pr-3 py-3 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 bg-white" placeholder="To" />
            </div>
        </div>

        <!-- Selection Actions Bar -->
        @if (count($selectedNotifications) > 0)
            <div class="flex items-center gap-3 p-4 bg-slate-100 rounded-2xl text-xs font-bold text-slate-700 transition-all shadow-sm">
                <span>{{ count($selectedNotifications) }} selected</span>
                <button wire:click="markSelectedAsRead" class="px-3.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">Mark Read</button>
                <button wire:click="deleteSelected" wire:confirm="Are you sure you want to delete these?" class="px-3.5 py-1.5 bg-red-50 text-red-700 rounded-xl hover:bg-red-100 transition-all">Delete</button>
            </div>
        @endif

        <!-- Card Container -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden p-6 space-y-4">
            <!-- Header Checkbox bar -->
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <input type="checkbox" wire:model.live="selectAll" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer" />
                <span class="text-xs font-bold text-slate-500">{{ $totalNotifications }} notifications</span>
            </div>

            <!-- List -->
            <div class="divide-y divide-slate-100/80">
                @forelse($notifications as $n)
                    <div class="py-5 flex items-start gap-4 hover:bg-slate-50/40 px-2 rounded-2xl transition-colors">
                        <!-- Checkbox -->
                        <div class="pt-1">
                            <input type="checkbox" wire:model.live="selectedNotifications" value="{{ $n->id }}" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer" />
                        </div>
                        
                        <!-- Icon representation matching mockup style (bell or user-plus depending on type) -->
                        <div class="mt-0.5 p-2.5 bg-slate-50 rounded-xl text-slate-600 flex-shrink-0">
                            @if ($n->type === 'code_redeemed')
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            @elseif ($n->type === 'signup')
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm font-extrabold text-slate-800">{{ $n->title }}</h4>
                                
                                @if (!$n->is_read)
                                    <span class="h-2 w-2 rounded-full bg-emerald-500" title="Unread"></span>
                                @endif

                                @if ($n->type === 'signup')
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-bold rounded-lg uppercase tracking-wider">Signup</span>
                                @endif
                            </div>

                            <p class="text-xs text-slate-500 max-w-2xl leading-relaxed">{{ $n->message }}</p>
                            <span class="text-[10px] text-slate-400 block mt-1">{{ $n->created_at->format('M j, Y • g:i A') }}</span>
                        </div>

                        <!-- Actions (Eye icon to mark read, and delete trash icon) -->
                        <div class="flex items-center gap-2.5 self-center">
                            @if (!$n->is_read)
                                <button wire:click="markAsRead({{ $n->id }})" class="text-slate-700 hover:text-emerald-700 transition-colors p-1" title="Mark Read">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            @endif
                            <button wire:click="delete({{ $n->id }})" wire:confirm="Delete notification?" class="text-slate-400 hover:text-red-600 transition-colors p-1" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-sm text-slate-400">
                        No notifications found.
                    </div>
                @endforelse
            </div>

            @if ($notifications->hasPages())
                <div class="pt-6 border-t border-slate-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

