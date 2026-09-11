@php
    $unreadCount = auth()->check() ? \App\Models\UserNotification::where('user_id', auth()->id())->whereNull('read_at')->count() : 0;
    $bookmarkCount = auth()->check() ? \App\Models\Bookmark::where('user_id', auth()->id())->count() : 0;
    $streakDays = auth()->user()?->study_streak_days ?? 0;
@endphp

<div class="dashboard-panel mb-6 sticky top-2 z-20 bg-white/95 px-2 shadow-lg shadow-slate-900/5 backdrop-blur-md sm:px-3">
    <div class="flex items-center justify-between gap-2 py-2">
        <!-- Tab bar with right-fade scroll indicator -->
        <div class="relative flex-1 min-w-0">
            <!-- Right-fade gradient — signals more tabs via scroll on mobile -->
            <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white/95 to-transparent z-10 pointer-events-none sm:hidden"></div>
            <nav class="flex items-center space-x-1 text-xs font-bold whitespace-nowrap overflow-x-auto scrollbar-none touch-scroll pr-6 sm:pr-0">
                <!-- Overview -->
                <a href="{{ route('dashboard') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Overview</span>
                </a>

                <!-- Practice CBT -->
                <a href="{{ route('exam.setup') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('exam.setup') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Practice</span>
                </a>

                <!-- History -->
                <a href="{{ route('dashboard.history') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard.history*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>History</span>
                </a>

                <!-- Performance Analytics -->
                <a href="{{ route('dashboard.performance') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard.performance') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="hidden sm:inline">Performance</span>
                    <span class="sm:hidden">Stats</span>
                </a>

                <!-- Bookmarks -->
                <a href="{{ route('dashboard.bookmarks') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard.bookmarks') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    <span>Bookmarks</span>
                    @if($bookmarkCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ request()->routeIs('dashboard.bookmarks') ? 'bg-white text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                            {{ $bookmarkCount }}
                        </span>
                    @endif
                </a>

                <!-- Study Streak -->
                <a href="{{ route('dashboard.streak') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard.streak') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>🔥</span>
                    <span>Streak</span>
                    @if($streakDays > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ request()->routeIs('dashboard.streak') ? 'bg-white text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                            {{ $streakDays }}d
                        </span>
                    @endif
                </a>

                <!-- Leaderboard -->
                <a href="{{ route('dashboard.leaderboard') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard.leaderboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>🏆</span>
                    <span class="hidden sm:inline">Leaderboard</span>
                    <span class="sm:hidden">Board</span>
                </a>

                <!-- Refer & Earn -->
                <a href="{{ route('dashboard.referrals') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard.referrals') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>🤝</span>
                    <span>Refer</span>
                </a>

                <!-- Notifications -->
                <a href="{{ route('dashboard.notifications') }}"
                   class="px-3 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('dashboard.notifications') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="hidden sm:inline">Notifications</span>
                    @if($unreadCount > 0)
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Action Buttons: compact on mobile, full on desktop -->
        <div class="flex items-center gap-1.5 flex-shrink-0">
            <a href="{{ route('download') }}"
               class="hidden md:flex px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all items-center gap-1.5 text-xs"
               title="Download Desktop App for Offline Practice">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Desktop App</span>
            </a>
            <a href="{{ route('exam.setup') }}"
               class="flex px-3 sm:px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold shadow-sm transition-all items-center gap-1.5 text-xs whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline">New Exam</span>
                <span class="sm:hidden">Start</span>
            </a>
        </div>
    </div>
</div>
