<div class="space-y-2">
    <a href="{{ route('dashboard') }}" 
       class="flex items-center gap-3 px-3.5 py-2 hover:bg-slate-100 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-xs transition-all">
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Student App</span>
    </a>

    <div class="p-3 bg-slate-50 border border-slate-200/60 rounded-2xl flex items-center justify-between">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-xs flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="truncate">
                <div class="font-bold text-slate-900 text-xs truncate">{{ auth()->user()->name ?? 'Administrator' }}</div>
                <div class="text-[10px] text-slate-400 capitalize">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
            @csrf
            <button type="submit" title="Logout" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
        </form>
    </div>
</div>
