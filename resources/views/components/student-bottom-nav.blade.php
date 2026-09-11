@auth
@if(!request()->is('admin*') && !request()->routeIs('exam.run*'))
<div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-200 px-3 py-2 shadow-2xl backdrop-blur-md bg-white/95 pb-[max(0.5rem,env(safe-area-inset-bottom))]">
    <div class="max-w-md mx-auto grid grid-cols-5 gap-1 items-center text-center">
        <!-- Home / Overview -->
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center py-1 px-1 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-900 font-semibold' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px]">Home</span>
        </a>

        <!-- Practice CBT -->
        <a href="{{ route('exam.setup') }}" 
           class="flex flex-col items-center py-1 px-1 rounded-xl transition-colors {{ request()->routeIs('exam.setup') && request('mode') !== 'mock' ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-900 font-semibold' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="text-[10px]">Practice</span>
        </a>

        <!-- Mock Exam -->
        <a href="{{ route('mock-exams') }}" 
           class="flex flex-col items-center py-1 px-1 rounded-xl transition-colors {{ request()->routeIs('mock-exams') || (request()->routeIs('exam.setup') && request('mode') === 'mock') ? 'text-amber-600 font-extrabold' : 'text-slate-500 hover:text-slate-900 font-semibold' }}">
            <div class="relative">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="absolute -top-1 -right-2 flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
            </div>
            <span class="text-[10px]">Mock</span>
        </a>

        <!-- Progress / Analytics -->
        <a href="{{ route('dashboard.performance') }}" 
           class="flex flex-col items-center py-1 px-1 rounded-xl transition-colors {{ request()->routeIs('dashboard.performance') || request()->routeIs('progress') ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-900 font-semibold' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-[10px]">Progress</span>
        </a>

        <!-- Profile / Settings -->
        <a href="{{ route('account.profile') }}" 
           class="flex flex-col items-center py-1 px-1 rounded-xl transition-colors {{ request()->routeIs('account.profile') || request()->routeIs('profile') ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-900 font-semibold' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px]">Profile</span>
        </a>
    </div>
</div>
@endif
@endauth
