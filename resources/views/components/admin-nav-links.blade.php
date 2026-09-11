@php
    $openReportsCount = $openReportsCount ?? \App\Models\QuestionReport::where('status', 'open')->count();
    $unreadNotificationsCount = $unreadNotificationsCount ?? \App\Models\AdminNotification::where('is_read', false)->count();
    $totalMessagesCount = $totalMessagesCount ?? \App\Models\ContactMessage::where('status', 'new')->count();
@endphp

<!-- Main Navigation Items -->
<nav class="space-y-1">
    <!-- 1. Dashboard -->
    <a href="{{ route('admin.dashboard') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
            <span>Dashboard</span>
        </div>
    </a>

    <!-- 2. Questions -->
    <a href="{{ route('admin.questions') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.questions') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.questions') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Questions</span>
        </div>
    </a>

    <!-- 3. Exams & Subjects -->
    <a href="{{ route('admin.exams-subjects') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.exams-subjects') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.exams-subjects') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>Exams & Subjects</span>
        </div>
    </a>

    <!-- 4. Users -->
    <a href="{{ route('admin.users') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.users') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.users') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>Users</span>
        </div>
    </a>

    <!-- 5. Subscriptions -->
    <a href="{{ route('admin.subscriptions') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.subscriptions') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.subscriptions') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            <span>Subscriptions</span>
        </div>
    </a>

    <!-- 6. Analytics -->
    <a href="{{ route('admin.analytics') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.analytics') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.analytics') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14"/></svg>
            <span>Analytics</span>
        </div>
    </a>

    <!-- 7. Messages -->
    <a href="{{ route('admin.messages') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.messages') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.messages') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2"/></svg>
            <span>Messages</span>
        </div>
        @if($totalMessagesCount > 0)
            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ request()->routeIs('admin.messages') ? 'bg-white text-emerald-800' : 'bg-emerald-100 text-emerald-700' }}">
                {{ $totalMessagesCount }}
            </span>
        @endif
    </a>

    <!-- 8. Reports -->
    <a href="{{ route('admin.reports') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.reports') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.reports') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Reports</span>
        </div>
        @if($openReportsCount > 0)
            <span class="px-2 py-0.5 text-[10px] font-black rounded-full {{ request()->routeIs('admin.reports') ? 'bg-white text-emerald-800' : 'bg-rose-500 text-white animate-pulse' }}">
                {{ $openReportsCount }}
            </span>
        @endif
    </a>

    <!-- 9. Purchase Codes -->
    <a href="{{ route('admin.purchase-codes') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.purchase-codes') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.purchase-codes') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5-3a2 2 0 00-2 2v7a2 2 0 002 2h5a2 2 0 002-2V9a2 2 0 00-2-2h-5z"/></svg>
            <span>Purchase Codes</span>
        </div>
    </a>

    <!-- 10. Bulk Seeder -->
    <a href="{{ route('admin.bulk-seeder') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.bulk-seeder') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.bulk-seeder') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.79M9 11h.01M15 11h.01M9 15h6"/></svg>
            <span>Bulk Seeder</span>
        </div>
    </a>

    <!-- 11. Notifications -->
    <a href="{{ route('admin.notifications') }}" 
       class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.notifications') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.notifications') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span>Notifications</span>
        </div>
        @if($unreadNotificationsCount > 0)
            <span class="px-2 py-0.5 text-[10px] font-black rounded-full {{ request()->routeIs('admin.notifications') ? 'bg-white text-emerald-800' : 'bg-rose-500 text-white' }}">
                {{ $unreadNotificationsCount }}
            </span>
        @endif
    </a>
</nav>

<!-- Administration Section -->
<div class="pt-4 border-t border-slate-100 space-y-1">
    <p class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Management</p>

    <a href="{{ route('admin.blog') }}" 
       class="flex items-center gap-3 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.blog*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('admin.blog*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        <span>Blog</span>
    </a>

    <a href="{{ route('admin.affiliates') }}" 
       class="flex items-center gap-3 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.affiliates*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('admin.affiliates*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <span>Affiliates</span>
    </a>

    <a href="{{ route('admin.settings') }}" 
       class="flex items-center gap-3 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('admin.settings') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <span>Settings</span>
    </a>

    <a href="{{ route('admin.activity-logs') }}" 
       class="flex items-center gap-3 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.activity-logs') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('admin.activity-logs') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span>Activity Logs</span>
    </a>
</div>
