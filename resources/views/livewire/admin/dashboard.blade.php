<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/60 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8"
     x-data="{
        revenueMonths: @js($revenueMonths),
        revenueData: @js($revenueData),
        chartInstance: null,
        initChart() {
            const ctx = document.getElementById('adminRevenueChart');
            if (!ctx) return;
            if (this.chartInstance) {
                this.chartInstance.destroy();
            }

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            this.chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.revenueMonths,
                    datasets: [{
                        label: 'Revenue',
                        data: this.revenueData,
                        borderColor: '#059669',
                        borderWidth: 2,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(241, 245, 249, 1)' },
                            ticks: {
                                callback: val => '₦' + Number(val).toLocaleString(),
                                font: { size: 10, weight: '600' },
                                color: '#94a3b8'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, weight: '600' },
                                color: '#94a3b8'
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 12, weight: 'bold' },
                            padding: 10,
                            displayColors: false,
                            callbacks: {
                                label: context => ' Revenue: ₦' + Number(context.parsed.y).toLocaleString()
                            }
                        }
                    }
                }
            });
        }
     }"
     x-init="setTimeout(() => initChart(), 150)">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 overflow-x-hidden font-sans max-w-7xl w-full">
        
        <!-- Flash Alert -->
        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl text-xs font-bold shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('message') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-sm font-bold">&times;</button>
            </div>
        @endif

        <!-- Executive Header & Quick Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-widest text-slate-400">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Admin Operations</span>
                    <span>&bull;</span>
                    <span class="text-emerald-600">Production Live</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight mt-1">
                    System Control Center
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Platform metrics, candidate telemetry, and curriculum repository
                </p>
            </div>
            
            <!-- Quick Toolbar -->
            <div class="flex flex-wrap items-center gap-2">
                <button wire:click="clearSystemCache" 
                        wire:confirm="Clear view, application, and route caches?"
                        wire:loading.attr="disabled"
                        class="px-3.5 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-xs transition-all flex items-center gap-1.5 active:scale-95 disabled:opacity-50"
                        title="Clear view & route cache">
                    <svg wire:loading.remove wire:target="clearSystemCache" class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.79M9 11h.01M15 11h.01M9 15h6"/></svg>
                    <svg wire:loading wire:target="clearSystemCache" class="w-3.5 h-3.5 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.79M9 11h.01M15 11h.01M9 15h6"/></svg>
                    <span>Clear Cache</span>
                </button>

                <button wire:click="exportUsers" 
                        class="px-3.5 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-xs transition-all flex items-center gap-1.5 active:scale-95">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export Users</span>
                </button>

                <a href="{{ route('admin.bulk-seeder') }}" 
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black shadow-sm shadow-emerald-600/20 transition-all flex items-center gap-1.5 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Seed Questions</span>
                </a>
            </div>
        </div>

        <!-- 4 Clean KPI Stat Cards in 1 Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- 1. Revenue -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Revenue</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-heading tracking-tight leading-none">
                    ₦{{ number_format($totalRevenue) }}
                </h3>
                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">{{ number_format($subscribersCount) }} paid accounts</span>
                    <span class="font-extrabold text-emerald-600">+₦{{ number_format($todayRevenue) }} today</span>
                </div>
            </div>

            <!-- 2. Students -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Candidates</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-heading tracking-tight leading-none">
                    {{ number_format($totalUsers) }}
                </h3>
                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">{{ $dau }} DAU &bull; {{ $wau }} WAU</span>
                    <span class="font-extrabold text-blue-600">+{{ $todayNewUsers }} today</span>
                </div>
            </div>

            <!-- 3. Tests Taken -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tests Completed</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-heading tracking-tight leading-none">
                    {{ number_format($totalTestsTaken) }}
                </h3>
                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">All practice modes</span>
                    <span class="font-extrabold text-amber-600">{{ $activeSessionsCount }} active now</span>
                </div>
            </div>

            <!-- 4. Questions in Bank -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Question Bank</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-heading tracking-tight leading-none">
                    {{ number_format($totalQuestions) }}
                </h3>
                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Seeded in database</span>
                    @if($flaggedQuestions > 0)
                        <span class="font-extrabold text-rose-600">{{ $flaggedQuestions }} flagged</span>
                    @else
                        <span class="font-extrabold text-emerald-600">All clean</span>
                    @endif
                </div>
            </div>

        </div>

        <!-- 2-Column Master Workspace (Left 2 Cols, Right 1 Col) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
            
            <!-- LEFT COLUMN: Primary Administration & Feeds (2 Cols) -->
            <div class="xl:col-span-2 space-y-6">
                
                <!-- 1. Quick Administrative Command Hub (6 Clean Cards) -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-slate-900 font-heading uppercase tracking-wider">
                            Management Command Hub
                        </h3>
                        <span class="text-xs text-slate-400 font-medium">Quick Navigation</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        
                        <!-- Questions -->
                        <a href="{{ route('admin.questions') }}" 
                           class="p-3.5 rounded-xl border border-slate-100 hover:border-emerald-300 hover:bg-emerald-50/30 transition-all flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-900 group-hover:text-emerald-700">Questions</div>
                                <div class="text-[11px] text-slate-400 truncate">Curate & edit</div>
                            </div>
                        </a>

                        <!-- Bulk Seeder -->
                        <a href="{{ route('admin.bulk-seeder') }}" 
                           class="p-3.5 rounded-xl border border-slate-100 hover:border-amber-300 hover:bg-amber-50/30 transition-all flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-900 group-hover:text-amber-700">Bulk Seeder</div>
                                <div class="text-[11px] text-slate-400 truncate">ALOC API auto-fetch</div>
                            </div>
                        </a>

                        <!-- Exams & Syllabi -->
                        <a href="{{ route('admin.exams-subjects') }}" 
                           class="p-3.5 rounded-xl border border-slate-100 hover:border-blue-300 hover:bg-blue-50/30 transition-all flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-900 group-hover:text-blue-700">Curricula</div>
                                <div class="text-[11px] text-slate-400 truncate">Exams & subjects</div>
                            </div>
                        </a>

                        <!-- Users -->
                        <a href="{{ route('admin.users') }}" 
                           class="p-3.5 rounded-xl border border-slate-100 hover:border-purple-300 hover:bg-purple-50/30 transition-all flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-900 group-hover:text-purple-700">Students</div>
                                <div class="text-[11px] text-slate-400 truncate">Accounts & roles</div>
                            </div>
                        </a>

                        <!-- Subscriptions -->
                        <a href="{{ route('admin.subscriptions') }}" 
                           class="p-3.5 rounded-xl border border-slate-100 hover:border-teal-300 hover:bg-teal-50/30 transition-all flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-900 group-hover:text-teal-700">Billing</div>
                                <div class="text-[11px] text-slate-400 truncate">Paystack ledger</div>
                            </div>
                        </a>

                        <!-- Reports -->
                        <a href="{{ route('admin.reports') }}" 
                           class="p-3.5 rounded-xl border border-slate-100 hover:border-rose-300 hover:bg-rose-50/30 transition-all flex items-center gap-3 group relative">
                            @if($openReportsCount > 0)
                                <span class="absolute top-2 right-2 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-500 text-white">
                                    {{ $openReportsCount }}
                                </span>
                            @endif
                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-900 group-hover:text-rose-700">Reports</div>
                                <div class="text-[11px] text-slate-400 truncate">Student feedback</div>
                            </div>
                        </a>

                    </div>
                </div>

                <!-- 2. Revenue Chart (Compact 200px Height) -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 font-heading uppercase tracking-wider">Revenue Trend (6 Months)</h3>
                            <p class="text-xs text-slate-400">Monthly subscription income in NGN (₦)</p>
                        </div>
                        <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                            ₦{{ number_format($totalRevenue) }} Total
                        </span>
                    </div>

                    <div class="relative h-48 w-full">
                        <canvas id="adminRevenueChart"></canvas>
                    </div>
                </div>

                <!-- 3. Real-Time Candidate Tests Feed -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 font-heading uppercase tracking-wider">Latest Candidate Submissions</h3>
                            <p class="text-xs text-slate-400">Live feed of completed practice and mock exams</p>
                        </div>
                        <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                            Live Stream
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentActivity->take(6) as $sess)
                            @php
                                $examSlug = strtolower($sess->exam->slug ?? $sess->exam->name ?? '');
                                $badgeColor = match(true) {
                                    str_contains($examSlug, 'jamb') || str_contains($examSlug, 'utme') => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    str_contains($examSlug, 'waec') || str_contains($examSlug, 'ssce') => 'bg-blue-50 text-blue-700 border-blue-200',
                                    str_contains($examSlug, 'neco') => 'bg-amber-50 text-amber-800 border-amber-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <div class="py-2.5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($sess->user->name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-slate-900 truncate">
                                                {{ $sess->user->name ?? 'Candidate' }}
                                            </span>
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase border {{ $badgeColor }}">
                                                {{ $sess->exam->slug ?? $sess->exam->name }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 truncate">
                                            <span class="capitalize font-medium">{{ $sess->mode }}</span> &bull; 
                                            {{ $sess->correct_count }}/{{ $sess->total_questions }} correct
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-xs font-black text-slate-900 block">
                                        {{ round($sess->score) }}{{ str_contains($examSlug, 'utme') ? '/400' : '%' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $sess->submitted_at?->diffForHumans() ?? 'just now' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">
                                No test sessions completed yet.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Operational Widgets & Health (1 Col) -->
            <div class="space-y-6">
                
                <!-- 1. Triage / Attention Center -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-900 font-heading">Action Center</span>
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full {{ ($openReportsCount + $unreadMessagesCount) > 0 ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $openReportsCount + $unreadMessagesCount }} Pending
                        </span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <a href="{{ route('admin.reports') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-rose-50/50 flex items-center justify-between transition-colors">
                            <span class="font-bold text-slate-700">Question Error Reports</span>
                            <span class="px-2 py-0.5 rounded-md font-black {{ $openReportsCount > 0 ? 'bg-rose-500 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $openReportsCount }}
                            </span>
                        </a>

                        <a href="{{ route('admin.messages') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-indigo-50/50 flex items-center justify-between transition-colors">
                            <span class="font-bold text-slate-700">Contact Inquiries</span>
                            <span class="px-2 py-0.5 rounded-md font-black {{ $unreadMessagesCount > 0 ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $unreadMessagesCount }}
                            </span>
                        </a>
                    </div>
                </div>

                <!-- 2. Curriculum Coverage Summary (Replaces the 60 endless cards!) -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-900 font-heading">Curriculum Saturation</span>
                        <a href="{{ route('admin.bulk-seeder') }}" class="text-[11px] font-extrabold text-emerald-600 hover:text-emerald-700">
                            Seed More &rarr;
                        </a>
                    </div>

                    <!-- Progress breakdown by exam board -->
                    <div class="space-y-3 text-xs">
                        <!-- JAMB UTME -->
                        <div>
                            <div class="flex justify-between font-bold mb-1">
                                <span class="text-slate-800">JAMB UTME</span>
                                <span class="text-slate-600 font-mono">{{ number_format($jambQuestionsCount) }} Qs</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                @php $jPercent = min(100, ($jambQuestionsCount / 800) * 100); @endphp
                                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ max(4, $jPercent) }}%"></div>
                            </div>
                        </div>

                        <!-- WAEC SSCE -->
                        <div>
                            <div class="flex justify-between font-bold mb-1">
                                <span class="text-slate-800">WAEC SSCE</span>
                                <span class="text-slate-600 font-mono">{{ number_format($waecQuestionsCount) }} Qs</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                @php $wPercent = min(100, ($waecQuestionsCount / 800) * 100); @endphp
                                <div class="bg-blue-500 h-full rounded-full" style="width: {{ max(4, $wPercent) }}%"></div>
                            </div>
                        </div>

                        <!-- NECO SSCE -->
                        <div>
                            <div class="flex justify-between font-bold mb-1">
                                <span class="text-slate-800">NECO SSCE</span>
                                <span class="text-slate-600 font-mono">{{ number_format($necoQuestionsCount) }} Qs</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                @php $nPercent = min(100, ($necoQuestionsCount / 800) * 100); @endphp
                                <div class="bg-amber-500 h-full rounded-full" style="width: {{ max(4, $nPercent) }}%"></div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.bulk-seeder') }}" class="block text-center py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-extrabold rounded-xl text-xs transition-colors border border-emerald-200/60">
                        Launch Batch Seeder
                    </a>
                </div>

                <!-- 3. Audience Engagement Stickiness -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-900 font-heading">User Engagement</span>
                        <span class="text-xs font-bold text-slate-400">Activity Ratios</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">DAU</span>
                            <span class="text-base font-black text-slate-900">{{ number_format($dau) }}</span>
                            <span class="text-[9px] text-slate-400 block mt-0.5">Daily</span>
                        </div>
                        <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">WAU</span>
                            <span class="text-base font-black text-slate-900">{{ number_format($wau) }}</span>
                            <span class="text-[9px] text-slate-400 block mt-0.5">7-Day</span>
                        </div>
                        <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">MAU</span>
                            <span class="text-base font-black text-slate-900">{{ number_format($mau) }}</span>
                            <span class="text-[9px] text-slate-400 block mt-0.5">30-Day</span>
                        </div>
                    </div>
                </div>

                <!-- 4. System Diagnostics -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3 text-xs">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-900 font-heading">System Diagnostics</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>

                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>ALOC API Engine:</span>
                            <span class="font-bold text-emerald-600">v1 Connected</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Database Questions:</span>
                            <span class="font-bold text-slate-900">{{ number_format($totalQuestions) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>App Cache:</span>
                            <button wire:click="clearSystemCache" class="font-bold text-emerald-600 hover:underline">
                                Purge Now
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Section 3: Compact Subject Inventory Matrix (Clean Table format) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs"
             x-data="{ activeTab: 'all', search: '' }">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black text-slate-900 font-heading uppercase tracking-wider">
                        Subject Question Inventory
                    </h3>
                    <p class="text-xs text-slate-400">Detailed question availability per exam curriculum</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               x-model="search" 
                               placeholder="Filter subject..." 
                               class="text-xs py-1.5 pl-7 pr-3 border border-slate-200 rounded-xl focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 w-36 sm:w-44">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <!-- Exam Tabs -->
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold">
                        <button type="button" @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500'" class="px-2.5 py-1 rounded-lg transition-all">All</button>
                        <button type="button" @click="activeTab = 'jamb'" :class="activeTab === 'jamb' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500'" class="px-2.5 py-1 rounded-lg transition-all">JAMB</button>
                        <button type="button" @click="activeTab = 'waec'" :class="activeTab === 'waec' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-500'" class="px-2.5 py-1 rounded-lg transition-all">WAEC</button>
                        <button type="button" @click="activeTab = 'neco'" :class="activeTab === 'neco' ? 'bg-white text-amber-700 shadow-xs' : 'text-slate-500'" class="px-2.5 py-1 rounded-lg transition-all">NECO</button>
                    </div>
                </div>
            </div>

            <!-- Compact Scrollable Table -->
            <div class="overflow-x-auto max-h-80 overflow-y-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead class="text-[10px] uppercase tracking-wider text-slate-400 bg-slate-50/80 sticky top-0 border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-3 font-bold">Subject</th>
                            <th class="py-2.5 px-3 font-bold">Exam Board</th>
                            <th class="py-2.5 px-3 font-bold text-center">Seeded Questions</th>
                            <th class="py-2.5 px-3 font-bold">Status</th>
                            <th class="py-2.5 px-3 font-bold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
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
                            <tr x-show="(activeTab === 'all' || activeTab === '{{ $tag }}') && ('{{ strtolower($subject->name) }}'.includes(search.toLowerCase()) || search === '')"
                                class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-2.5 px-3 font-bold text-slate-900">
                                    {{ $subject->name }}
                                </td>
                                <td class="py-2.5 px-3 text-slate-500">
                                    {{ $subject->exam->name }}
                                </td>
                                <td class="py-2.5 px-3 text-center font-black {{ $subject->questions_count > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ number_format($subject->questions_count) }}
                                </td>
                                <td class="py-2.5 px-3">
                                    @if($subject->questions_count >= 100)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700">Well Seeded</span>
                                    @elseif($subject->questions_count > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800">Growing</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700">Needs Questions</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-right space-x-2">
                                    <a href="{{ route('admin.questions', ['subject_id' => $subject->id]) }}" class="text-slate-600 hover:text-slate-900 font-bold">
                                        View
                                    </a>
                                    @if($subject->questions_count == 0)
                                        <a href="{{ route('admin.bulk-seeder') }}" class="text-emerald-600 hover:text-emerald-700 font-black">
                                            Seed &rarr;
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">No subject records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- Load Chart.js CDN for Revenue Telemetry -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
