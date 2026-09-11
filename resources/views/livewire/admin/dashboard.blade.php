<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 w-full min-w-0 p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 overflow-x-hidden font-sans">
        
        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl text-xs sm:text-sm font-bold shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <!-- Top Header & Breadcrumb / Quick Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/70">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    <span>Control Center</span>
                    <span>&bull;</span>
                    <span class="text-emerald-600">Live Overview</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-950 font-heading tracking-tight mt-0.5">Admin Dashboard</h1>
            </div>
            
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                <button wire:click="clearSystemCache" 
                        wire:confirm="Clear all view, application, and route caches?"
                        class="px-3 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all flex items-center gap-1.5"
                        title="Clear view & route cache">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.79M9 11h.01M15 11h.01M9 15h6"/></svg>
                    <span>Clear Cache</span>
                </button>
                <button wire:click="exportUsers" class="px-3 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Users CSV</span>
                </button>
                <button wire:click="exportPayments" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm shadow-emerald-600/10 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Payments CSV</span>
                </button>
            </div>
        </div>

        <!-- Section 1: Top 4 Commercial KPIs -->
        <div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <!-- 1. Total Registered Users -->
                <div class="bg-white border border-slate-200/70 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <span class="text-xs font-bold text-slate-500">Total Users</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-xl sm:text-3xl font-black text-slate-900 font-heading leading-tight">{{ number_format($totalUsers) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Registered accounts</p>
                </div>

                <!-- 2. Active Paid Subscribers -->
                <div class="bg-white border border-slate-200/70 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <span class="text-xs font-bold text-slate-500">Active Paid</span>
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-xl sm:text-3xl font-black text-slate-900 font-heading leading-tight">{{ number_format($subscribersCount) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Premium subscribers</p>
                </div>

                <!-- 3. Gross Revenue -->
                <div class="bg-white border border-slate-200/70 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <span class="text-xs font-bold text-slate-500">Gross Revenue</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs">
                            ₦
                        </div>
                    </div>
                    <h3 class="text-xl sm:text-3xl font-black text-slate-900 font-heading leading-tight">₦{{ number_format($totalRevenue, 0) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Lifetime collections</p>
                </div>

                <!-- 4. Tests Completed -->
                <div class="bg-white border border-slate-200/70 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <span class="text-xs font-bold text-slate-500">Tests Completed</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-xl sm:text-3xl font-black text-slate-900 font-heading leading-tight">{{ number_format($totalTestsTaken) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Student exam sessions</p>
                </div>
            </div>

            <!-- Secondary Operational Metric Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mt-3 sm:mt-4">
                <!-- Question Bank Size -->
                <div class="bg-slate-100/70 border border-slate-200/50 p-3.5 rounded-xl flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 block">Questions Bank</span>
                        <span class="text-lg font-black text-slate-800">{{ number_format($totalQuestions) }}</span>
                    </div>
                    <a href="{{ route('admin.questions') }}" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800">Manage &rarr;</a>
                </div>

                <!-- Flagged Reports -->
                <div class="bg-slate-100/70 border border-slate-200/50 p-3.5 rounded-xl flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 block">Flagged Questions</span>
                        <span class="text-lg font-black {{ $flaggedQuestions > 0 ? 'text-amber-600' : 'text-slate-800' }}">{{ number_format($flaggedQuestions) }}</span>
                    </div>
                    <a href="{{ route('admin.reports') }}" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800">Review &rarr;</a>
                </div>

                <!-- Daily / Monthly Active -->
                <div class="bg-slate-100/70 border border-slate-200/50 p-3.5 rounded-xl flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 block">DAU / MAU</span>
                        <span class="text-lg font-black text-slate-800">{{ $dau }} <span class="text-xs text-slate-400 font-medium">/ {{ $mau }}</span></span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Active</span>
                </div>

                <!-- Messages -->
                <div class="bg-slate-100/70 border border-slate-200/50 p-3.5 rounded-xl flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 block">Inquiries</span>
                        <span class="text-lg font-black text-slate-800">{{ number_format(\App\Models\ContactMessage::count()) }}</span>
                    </div>
                    <a href="{{ route('admin.messages') }}" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800">Inbox &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Section 2: Quick Management Actions & Recent Candidate Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- Quick Actions (5 cols) -->
            <div class="lg:col-span-5 self-start w-full bg-white border border-slate-200/70 p-4 sm:p-5 rounded-3xl shadow-sm">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 font-heading mb-1">Quick Actions</h3>
                    <p class="text-xs text-slate-400 mb-5">Frequently accessed administrative modules</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <a href="{{ route('admin.questions') }}" class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:border-emerald-300 hover:bg-emerald-50/40 transition-all group">
                            <span class="w-9 h-9 shrink-0 rounded-xl bg-emerald-100/70 text-emerald-700 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span><span class="block text-xs font-bold text-slate-800">Questions</span><span class="block text-[10px] text-slate-400">Curate & import</span></span>
                        </a>

                        <a href="{{ route('admin.bulk-seeder') }}" class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:border-amber-300 hover:bg-amber-50/40 transition-all group">
                            <span class="w-9 h-9 shrink-0 rounded-xl bg-amber-100/70 text-amber-700 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </span>
                            <span><span class="block text-xs font-bold text-slate-800">Bulk Seeder</span><span class="block text-[10px] text-slate-400">ALOC API auto-fetch</span></span>
                        </a>

                        <a href="{{ route('admin.exams-subjects') }}" class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:border-blue-300 hover:bg-blue-50/40 transition-all group">
                            <span class="w-9 h-9 shrink-0 rounded-xl bg-blue-100/70 text-blue-700 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </span>
                            <span><span class="block text-xs font-bold text-slate-800">Exams & Subjects</span><span class="block text-[10px] text-slate-400">Tiers & syllabus</span></span>
                        </a>

                        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:border-purple-300 hover:bg-purple-50/40 transition-all group">
                            <span class="w-9 h-9 shrink-0 rounded-xl bg-purple-100/70 text-purple-700 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </span>
                            <span><span class="block text-xs font-bold text-slate-800">Users</span><span class="block text-[10px] text-slate-400">Accounts & roles</span></span>
                        </a>

                        <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:border-teal-300 hover:bg-teal-50/40 transition-all group">
                            <span class="w-9 h-9 shrink-0 rounded-xl bg-teal-100/70 text-teal-700 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </span>
                            <span><span class="block text-xs font-bold text-slate-800">Subscriptions</span><span class="block text-[10px] text-slate-400">Payments & plans</span></span>
                        </a>

                        <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:border-rose-300 hover:bg-rose-50/40 transition-all group">
                            <span class="w-9 h-9 shrink-0 rounded-xl bg-rose-100/70 text-rose-700 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14"/></svg>
                            </span>
                            <span><span class="block text-xs font-bold text-slate-800">Analytics</span><span class="block text-[10px] text-slate-400">Metrics & revenue</span></span>
                        </a>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>ALOC Question Importer</span>
                    <a href="{{ route('admin.bulk-seeder') }}" class="font-extrabold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        Run Batch Import &rarr;
                    </a>
                </div>
            </div>

            <!-- Recent Activity (7 cols) -->
            <div class="lg:col-span-7 bg-white border border-slate-200/70 p-6 rounded-3xl shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 font-heading">Recent Candidate Activity</h3>
                        <p class="text-xs text-slate-400">Latest completed tests across exams</p>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                        Live Stream
                    </span>
                </div>

                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto pr-1">
                    @forelse ($recentActivity as $sess)
                        <div class="py-3 flex justify-between items-start gap-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                                    {{ strtoupper(substr($sess->user->name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900">{{ $sess->user->name ?? 'Candidate' }}</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        <span class="font-bold text-emerald-600 uppercase text-[10px] tracking-wider">{{ $sess->mode }}</span> &bull; 
                                        {{ collect($sess->subjects)->map(fn($id) => $subjectsMap[$id] ?? '')->filter()->implode(', ') }}
                                        <span class="text-slate-400">({{ $sess->exam->name }})</span>
                                    </p>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">{{ $sess->submitted_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="py-12 text-center text-xs text-slate-400">
                            No student sessions activity recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Section 3: Question Coverage Organised by Exam Tabs -->
        <div class="bg-white border border-slate-200/70 p-6 rounded-3xl shadow-sm"
             x-data="{ activeTab: 'all', search: '' }">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 font-heading">Question Bank Coverage</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Inventory of locally seeded questions across Nigerian exam boards</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search Filter -->
                    <div class="relative">
                        <input type="text" 
                               x-model="search" 
                               placeholder="Filter subject..." 
                               class="text-xs py-1.5 pl-8 pr-3 border border-slate-200 rounded-xl focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 w-44">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <!-- Exam Tabs -->
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold">
                        <button type="button" 
                                @click="activeTab = 'all'" 
                                :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1 rounded-lg transition-all">
                            All
                        </button>
                        <button type="button" 
                                @click="activeTab = 'jamb'" 
                                :class="activeTab === 'jamb' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1 rounded-lg transition-all">
                            JAMB
                        </button>
                        <button type="button" 
                                @click="activeTab = 'waec'" 
                                :class="activeTab === 'waec' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1 rounded-lg transition-all">
                            WAEC
                        </button>
                        <button type="button" 
                                @click="activeTab = 'neco'" 
                                :class="activeTab === 'neco' ? 'bg-white text-amber-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1 rounded-lg transition-all">
                            NECO
                        </button>
                    </div>

                    <a href="{{ route('admin.bulk-seeder') }}" class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 rounded-xl text-xs font-bold transition-colors">
                        + Import Questions
                    </a>
                </div>
            </div>

            <!-- Subject Cards Grid with Tab & Search filtering -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[500px] overflow-y-auto pr-1">
                @forelse ($questionCoverage as $subject)
                    @php
                        $examCode = strtolower($subject->exam->name ?? '');
                        $tag = 'other';
                        if (str_contains($examCode, 'jamb') || str_contains($examCode, 'utme')) {
                            $tag = 'jamb';
                        } elseif (str_contains($examCode, 'waec') || str_contains($examCode, 'ssce')) {
                            $tag = 'waec';
                        } elseif (str_contains($examCode, 'neco')) {
                            $tag = 'neco';
                        }
                    @endphp
                    <div x-show="(activeTab === 'all' || activeTab === '{{ $tag }}') && ('{{ strtolower($subject->name) }}'.includes(search.toLowerCase()) || search === '')"
                         class="p-4 border border-slate-100 hover:border-slate-300 bg-white hover:bg-slate-50/50 rounded-2xl transition-all shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-slate-800 truncate pr-2">{{ $subject->name }}</span>
                            <span class="text-xs font-black {{ $subject->questions_count > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ number_format($subject->questions_count) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between text-[10px] text-slate-400 uppercase font-semibold mb-2.5">
                            <span>{{ $subject->exam->name }}</span>
                            <span>{{ $subject->questions_count >= 100 ? 'Well Seeded' : ($subject->questions_count > 0 ? 'Partial' : 'Needs Questions') }}</span>
                        </div>
                        
                        <!-- Progress visual -->
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            @php
                                $percent = min(100, ($subject->questions_count / 200) * 100);
                                $barColor = $subject->questions_count >= 100 ? 'bg-emerald-500' : ($subject->questions_count > 0 ? 'bg-amber-500' : 'bg-slate-200');
                            @endphp
                            <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ max(4, $percent) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-xs text-slate-400">
                        No subjects registered in system.
                    </div>
                @endforelse
            </div>
        </div>

    </main>
</div>
