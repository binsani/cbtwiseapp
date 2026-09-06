<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl text-xs font-bold shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Dashboard</h1>
                <p class="text-xs text-slate-500 mt-0.5">Overview of CBTWise platform</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="clearSystemCache" 
                        wire:confirm="Clear all view, application, and route caches?"
                        class="px-3.5 py-2 border border-slate-200 hover:border-slate-300 rounded-2xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5"
                        title="Clear view & route cache">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.79M9 11h.01M15 11h.01M9 15h6"/></svg>
                    <span>Clear Cache</span>
                </button>
                <button wire:click="exportUsers" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-colors">
                    Export Users CSV
                </button>
                <button wire:click="exportPayments" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-sm shadow-emerald-600/10 transition-colors">
                    Export Payments CSV
                </button>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <!-- Metric 1 -->
            <div class="bg-white border border-slate-100/80 p-5 rounded-2xl shadow-sm hover:shadow transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 font-heading leading-tight">{{ $totalUsers }}</h3>
                <p class="text-xs font-bold text-slate-500 mt-1">Total Users</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Registered accounts</p>
            </div>

            <!-- Metric 2 -->
            <div class="bg-white border border-slate-100/80 p-5 rounded-2xl shadow-sm hover:shadow transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 font-heading leading-tight">{{ $subscribersCount }}</h3>
                <p class="text-xs font-bold text-slate-500 mt-1">Subscribers</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Active paid plans</p>
            </div>

            <!-- Metric 3 -->
            <div class="bg-white border border-slate-100/80 p-5 rounded-2xl shadow-sm hover:shadow transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 font-heading leading-tight">{{ number_format($totalQuestions) }}</h3>
                <p class="text-xs font-bold text-slate-500 mt-1">Questions</p>
                <p class="text-[10px] text-slate-400 mt-0.5">In question bank</p>
            </div>

            <!-- Metric 4 -->
            <div class="bg-white border border-slate-100/80 p-5 rounded-2xl shadow-sm hover:shadow transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 font-heading leading-tight">{{ $totalTestsTaken }}</h3>
                <p class="text-xs font-bold text-slate-500 mt-1">Tests Taken</p>
                <p class="text-[10px] text-slate-400 mt-0.5">All time</p>
            </div>

            <!-- Metric 5 -->
            <div class="bg-white border border-slate-100/80 p-5 rounded-2xl shadow-sm hover:shadow transition-shadow col-span-2 md:col-span-1">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 font-heading leading-tight">{{ $flaggedQuestions }}</h3>
                <p class="text-xs font-bold text-slate-500 mt-1">Reported</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Flagged questions</p>
            </div>
        </div>

        <!-- Row: Quick Actions & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Quick Actions -->
            <div class="bg-white border border-slate-100/80 p-6 rounded-3xl shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 font-heading mb-6">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.questions') }}" class="flex items-center gap-3 p-4 border border-slate-100 hover:bg-slate-50 rounded-2xl text-sm font-semibold text-slate-700 transition-colors">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">📋</span>
                        Manage Questions
                    </a>
                    <a href="{{ route('admin.exams-subjects') }}" class="flex items-center gap-3 p-4 border border-slate-100 hover:bg-slate-50 rounded-2xl text-sm font-semibold text-slate-700 transition-colors">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">📖</span>
                        Exams & Subjects
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-4 border border-slate-100 hover:bg-slate-50 rounded-2xl text-sm font-semibold text-slate-700 transition-colors">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">👥</span>
                        View Users
                    </a>
                    <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 p-4 border border-slate-100 hover:bg-slate-50 rounded-2xl text-sm font-semibold text-slate-700 transition-colors">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">💳</span>
                        Subscriptions
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white border border-slate-100/80 p-6 rounded-3xl shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 font-heading mb-4">Recent Activity</h3>
                <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto pr-2 space-y-3">
                    @forelse ($recentActivity as $sess)
                        <div class="py-2.5 flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-sm text-slate-800">{{ $sess->user->name ?? 'Candidate' }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <span class="capitalize font-semibold text-emerald-600">{{ $sess->mode }}</span>: 
                                    {{ collect($sess->subjects)->map(fn($id) => $subjectsMap[$id] ?? '')->filter()->implode(', ') }} 
                                    ({{ $sess->exam->name }})
                                </p>
                            </div>
                            <span class="text-xs text-slate-400 whitespace-nowrap">{{ $sess->submitted_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-slate-400">
                            No student sessions activity recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Question Coverage Grid -->
        <div class="bg-white border border-slate-100/80 p-6 rounded-3xl shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 font-heading mb-6">Local Question Coverage</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($questionCoverage as $subject)
                    <div class="p-4 border border-slate-50 bg-slate-50/20 rounded-2xl">
                        <div class="flex justify-between items-baseline mb-2">
                            <span class="text-sm font-bold text-slate-800 truncate pr-2">{{ $subject->name }}</span>
                            <span class="text-sm font-black text-emerald-600">{{ $subject->questions_count }}</span>
                        </div>
                        <p class="text-[10px] text-slate-400 capitalize mb-3">{{ $subject->exam->name }}</p>
                        
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <!-- Show relative fullness cap at 200 questions for coverage progress visualization -->
                            @php
                                $percent = min(100, ($subject->questions_count / 200) * 100);
                            @endphp
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-8 text-center text-sm text-slate-400">
                        No subjects registered.
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
