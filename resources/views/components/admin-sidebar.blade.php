@php
    $openReportsCount = \App\Models\QuestionReport::where('status', 'open')->count();
    $unreadNotificationsCount = \App\Models\AdminNotification::where('is_read', false)->count();
    $totalMessagesCount = \App\Models\ContactMessage::where('status', 'new')->count();
    $totalAlertsCount = $openReportsCount + $totalMessagesCount;
@endphp

<div x-data="{ mobileOpen: false }">
    <!-- Mobile Sticky Top App Bar (Visible below lg) -->
    <div class="lg:hidden sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 py-3 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <button @click="mobileOpen = true" 
                    type="button"
                    class="p-2 -ml-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none relative"
                    aria-label="Open Admin Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                @if($totalAlertsCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                @endif
            </button>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <img src="/logo.png" alt="CBTWise" class="w-7 h-7 rounded-lg shadow-sm">
                <span class="text-base font-black tracking-tight font-heading text-slate-900">
                    CBT<span class="text-emerald-600">Wise</span>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full ml-1 uppercase tracking-wider">Admin</span>
                </span>
            </a>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" 
               class="text-xs font-bold text-slate-600 hover:text-emerald-600 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl transition-colors flex items-center gap-1">
                <span>Student App</span>
                <span class="text-[10px]">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Mobile Slide-Over Backdrop -->
    <div x-show="mobileOpen" 
         x-cloak 
         @click="mobileOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"></div>

    <!-- Mobile Slide-Over Drawer -->
    <div x-show="mobileOpen" 
         x-cloak 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 max-w-xs w-full bg-white z-50 shadow-2xl flex flex-col lg:hidden">
        
        <!-- Mobile Drawer Header -->
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="/logo.png" alt="CBTWise" class="w-7 h-7 rounded-xl shadow-sm">
                <span class="text-sm font-black font-heading text-slate-900">Admin Control Center</span>
            </div>
            <button @click="mobileOpen = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Drawer Navigation Items -->
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
            @include('components.admin-nav-links')
        </div>

        <!-- Mobile Drawer Footer -->
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            @include('components.admin-user-card')
        </div>
    </div>

    <!-- Desktop Permanent Sidebar (Visible on lg+) -->
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-slate-200/80 flex-shrink-0 sticky top-0 h-screen overflow-y-auto p-5 space-y-6 font-sans">
        <!-- Brand / Header -->
        <div class="flex items-center justify-between px-2 pt-1">
            <div class="flex items-center gap-2.5">
                <img src="/logo.png" alt="CBTWise" class="w-8 h-8 rounded-xl shadow-sm">
                <div>
                    <span class="text-base font-black tracking-tight font-heading text-slate-900 block leading-tight">
                        CBT<span class="text-emerald-600">Wise</span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Administration</span>
                </div>
            </div>
            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full uppercase tracking-wider">Staff</span>
        </div>

        <!-- Desktop Navigation Items -->
        <div class="flex-1 space-y-6 overflow-y-auto pr-1 scrollbar-thin">
            @include('components.admin-nav-links')
        </div>

        <!-- Desktop Sidebar Footer -->
        <div class="pt-4 border-t border-slate-100">
            @include('components.admin-user-card')
        </div>
    </aside>
</div>
