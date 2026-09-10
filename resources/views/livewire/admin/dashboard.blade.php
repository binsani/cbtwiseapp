<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8"
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

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 240);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

            this.chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.revenueMonths,
                    datasets: [{
                        label: 'Revenue (NGN)',
                        data: this.revenueData,
                        borderColor: '#059669',
                        borderWidth: 2.5,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4.5,
                        pointHoverRadius: 6.5,
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
                                callback: function(val) {
                                    return '₦' + Number(val).toLocaleString();
                                },
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
                                label: function(context) {
                                    return ' Revenue: ₦' + Number(context.parsed.y).toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
     }"
     x-init="setTimeout(() => initChart(), 120)">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8 overflow-x-hidden font-sans max-w-7xl w-full">
        
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

        <!-- Executive Header & Quick Toolbar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-200/70">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-widest text-slate-400">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-slate-600">Executive Console</span>
                    <span>&bull;</span>
                    <span class="text-emerald-600 font-black">Live Production</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-950 font-heading tracking-tight mt-1">
                    System Control Center
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Real-time academic metrics, question repository, and candidate telemetry
                </p>
            </div>
            
            <!-- Quick Actions Toolbar -->
            <div class="flex flex-wrap items-center gap-2">
                <button wire:click="clearSystemCache" 
                        wire:confirm="Are you sure you want to clear view, application, and route caches?"
                        wire:loading.attr="disabled"
                        class="px-3.5 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all flex items-center gap-1.5 active:scale-95 disabled:opacity-50"
                        title="Purge cached views and route trees">
                    <svg wire:loading.remove wire:target="clearSystemCache" class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.79M9 11h.01M15 11h.01M9 15h6"/></svg>
                    <svg wire:loading wire:target="clearSystemCache" class="w-3.5 h-3.5 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 4.79M9 11h.01M15 11h.01M9 15h6"/></svg>
                    <span>Clear Cache</span>
                </button>

                <button wire:click="exportUsers" 
                        class="px-3.5 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all flex items-center gap-1.5 active:scale-95"
                        title="Download candidate dataset in CSV">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Users CSV</span>
                </button>

                <button wire:click="exportPayments" 
                        class="px-3.5 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all flex items-center gap-1.5 active:scale-95"
                        title="Download revenue ledger in CSV">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m0 0v6m0-6H9m12 8a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Payments CSV</span>
                </button>

                <a href="{{ route('admin.bulk-seeder') }}" 
                   class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-black shadow-sm shadow-emerald-600/20 transition-all flex items-center gap-1.5 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Seed Questions</span>
                </a>
            </div>
        </div>

        <!-- Section 1: Executive KPI Stat Cards (5 Cards) -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- 1. Total Revenue -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Revenue</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight leading-none">
                        ₦{{ number_format($totalRevenue) }}
                    </h3>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-medium">{{ number_format($subscribersCount) }} active paid</span>
                    <span class="px-2 py-0.5 rounded-full font-extrabold bg-emerald-50 text-emerald-700">
                        +₦{{ number_format($todayRevenue) }}
                    </span>
                </div>
            </div>

            <!-- 2. Candidates & Growth -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Students</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight leading-none">
                        {{ number_format($totalUsers) }}
                    </h3>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-medium">{{ $dau }} DAU / {{ $wau }} WAU</span>
                    <span class="px-2 py-0.5 rounded-full font-extrabold bg-blue-50 text-blue-700">
                        +{{ $todayNewUsers }} today
                    </span>
                </div>
            </div>

            <!-- 3. Exam Sessions -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tests Taken</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight leading-none">
                        {{ number_format($totalTestsTaken) }}
                    </h3>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-medium">Submitted CBTs</span>
                    <span class="px-2 py-0.5 rounded-full font-extrabold bg-amber-50 text-amber-800 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>{{ $activeSessionsCount }} active</span>
                    </span>
                </div>
            </div>

            <!-- 4. Questions in Bank -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Question Bank</span>
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight leading-none">
                        {{ number_format($totalQuestions) }}
                    </h3>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-medium">Archived items</span>
                    @if($flaggedQuestions > 0)
                        <span class="px-2 py-0.5 rounded-full font-extrabold bg-rose-50 text-rose-700">
                            {{ $flaggedQuestions }} flagged
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full font-extrabold bg-emerald-50 text-emerald-700">
                            Clean
                        </span>
                    @endif
                </div>
            </div>

            <!-- 5. Triage & Pending Support -->
            <div class="bg-white border border-slate-200/80 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between group col-span-2 lg:col-span-1">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Attention</span>
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight leading-none">
                        {{ $openReportsCount + $unreadMessagesCount }}
                    </h3>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <a href="{{ route('admin.reports') }}" class="font-bold text-rose-600 hover:underline">
                        {{ $openReportsCount }} reports
                    </a>
                    <a href="{{ route('admin.messages') }}" class="font-bold text-slate-600 hover:underline">
                        {{ $unreadMessagesCount }} msgs
                    </a>
                </div>
            </div>

        </div>

        <!-- Section 2: Visual Telemetry (Revenue Trends + Exam Popularity) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Revenue & Subscription Trends (7 Cols) -->
            <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-base font-black text-slate-900 font-heading">Financial Telemetry</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Verified Paystack subscription revenue over the last 6 months</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl text-xs font-black">
                            ₦{{ number_format($totalRevenue) }} Total
                        </span>
                    </div>
                </div>

                <!-- Chart Canvas Container -->
                <div class="relative h-64 w-full mt-2">
                    <canvas id="adminRevenueChart"></canvas>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs text-slate-500 gap-2">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            <span class="font-bold text-slate-700">6-Month Trend</span>
                        </span>
                        <span class="text-slate-400">Currency: NGN (₦)</span>
                    </div>
                    <a href="{{ route('admin.subscriptions') }}" class="font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        <span>View Ledger</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Exam Popularity & Engagement Ratio (5 Cols) -->
            <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-black text-slate-900 font-heading">Exam Popularity</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Student traffic distribution by examination body</p>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                            Session Share
                        </span>
                    </div>

                    <!-- Exam distribution list -->
                    <div class="space-y-3.5">
                        @forelse($examDistribution as $examItem)
                            <div>
                                <div class="flex justify-between items-center text-xs font-bold mb-1">
                                    <span class="text-slate-800">{{ $examItem['name'] }}</span>
                                    <span class="text-slate-600 font-mono">{{ number_format($examItem['count']) }} ({{ $examItem['percent'] }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    @php
                                        $barBg = match(true) {
                                            str_contains(strtolower($examItem['name']), 'jamb') || str_contains(strtolower($examItem['name']), 'utme') => 'bg-emerald-500',
                                            str_contains(strtolower($examItem['name']), 'waec') || str_contains(strtolower($examItem['name']), 'ssce') => 'bg-blue-500',
                                            str_contains(strtolower($examItem['name']), 'neco') => 'bg-amber-500',
                                            default => 'bg-purple-500',
                                        };
                                    @endphp
                                    <div class="{{ $barBg }} h-full rounded-full transition-all duration-500" style="width: {{ max(4, $examItem['percent']) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-slate-400">
                                No exam session history recorded yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Engagement Gauge Box -->
                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100 space-y-3">
                    <div class="flex items-center justify-between text-xs font-black text-slate-900">
                        <span>Audience Engagement Stickiness</span>
                        <span class="text-emerald-700">{{ $mau > 0 ? round(($dau / $mau) * 100, 1) : 0 }}% DAU/MAU</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2 bg-white rounded-xl border border-slate-200/60 shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">Today</span>
                            <span class="text-sm font-black text-slate-900">{{ number_format($dau) }}</span>
                        </div>
                        <div class="p-2 bg-white rounded-xl border border-slate-200/60 shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">7 Days</span>
                            <span class="text-sm font-black text-slate-900">{{ number_format($wau) }}</span>
                        </div>
                        <div class="p-2 bg-white rounded-xl border border-slate-200/60 shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">30 Days</span>
                            <span class="text-sm font-black text-slate-900">{{ number_format($mau) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Section 3: Administrative Command Hub (Fast Shortcuts) -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-black text-slate-900 font-heading">Management Command Hub</h3>
                    <p class="text-xs text-slate-400 mt-0.5">High-frequency management workflows and platform controllers</p>
                </div>
                <span class="text-xs font-bold text-slate-400 hidden sm:inline">8 Modules Available</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                
                <!-- 1. Question Bank -->
                <a href="{{ route('admin.questions') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-emerald-300 hover:bg-emerald-50/40 hover:-translate-y-0.5 transition-all group shadow-xs">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-100/80 text-emerald-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-emerald-700">Questions</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">Curate bank</span>
                </a>

                <!-- 2. Bulk Seeder -->
                <a href="{{ route('admin.bulk-seeder') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-amber-300 hover:bg-amber-50/40 hover:-translate-y-0.5 transition-all group shadow-xs">
                    <div class="w-11 h-11 rounded-2xl bg-amber-100/80 text-amber-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-amber-700">Bulk Seeder</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">ALOC Station</span>
                </a>

                <!-- 3. Exams & Syllabi -->
                <a href="{{ route('admin.exams-subjects') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-blue-300 hover:bg-blue-50/40 hover:-translate-y-0.5 transition-all group shadow-xs">
                    <div class="w-11 h-11 rounded-2xl bg-blue-100/80 text-blue-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-blue-700">Curricula</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">Boards & tiers</span>
                </a>

                <!-- 4. Candidates -->
                <a href="{{ route('admin.users') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-purple-300 hover:bg-purple-50/40 hover:-translate-y-0.5 transition-all group shadow-xs">
                    <div class="w-11 h-11 rounded-2xl bg-purple-100/80 text-purple-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-purple-700">Users</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">Roles & bans</span>
                </a>

                <!-- 5. Subscriptions -->
                <a href="{{ route('admin.subscriptions') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-teal-300 hover:bg-teal-50/40 hover:-translate-y-0.5 transition-all group shadow-xs">
                    <div class="w-11 h-11 rounded-2xl bg-teal-100/80 text-teal-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-teal-700">Billing</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">Subscriptions</span>
                </a>

                <!-- 6. Question Error Reports -->
                <a href="{{ route('admin.reports') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-rose-300 hover:bg-rose-50/40 hover:-translate-y-0.5 transition-all group shadow-xs relative">
                    @if($openReportsCount > 0)
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full animate-ping"></span>
                    @endif
                    <div class="w-11 h-11 rounded-2xl bg-rose-100/80 text-rose-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-rose-700">Reports</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $openReportsCount }} open</span>
                </a>

                <!-- 7. Support Messages -->
                <a href="{{ route('admin.messages') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-indigo-300 hover:bg-indigo-50/40 hover:-translate-y-0.5 transition-all group shadow-xs">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-100/80 text-indigo-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-indigo-700">Messages</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $unreadMessagesCount }} new</span>
                </a>

                <!-- 8. System Settings -->
                <a href="{{ route('admin.settings') }}" 
                   class="flex flex-col items-center text-center p-4 rounded-2xl border border-slate-100 hover:border-slate-400 hover:bg-slate-50 hover:-translate-y-0.5 transition-all group shadow-xs">
                    <div class="w-11 h-11 rounded-2xl bg-slate-200/80 text-slate-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-900 group-hover:text-slate-800">Settings</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 font-medium">Config & keys</span>
                </a>

            </div>
        </div>

        <!-- Section 4: Live Activity Telemetry & Operational Health -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Live Candidate Activity (7 Cols) -->
            <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-black text-slate-900 font-heading">Real-Time Candidate Feed</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Most recent examination sessions completed on CBTWise</p>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Telemetry</span>
                    </span>
                </div>

                <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto pr-1 space-y-1">
                    @forelse ($recentActivity as $sess)
                        @php
                            $examSlug = strtolower($sess->exam->slug ?? $sess->exam->name ?? '');
                            $badgeColor = match(true) {
                                str_contains($examSlug, 'jamb') || str_contains($examSlug, 'utme') => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                str_contains($examSlug, 'waec') || str_contains($examSlug, 'ssce') => 'bg-blue-50 text-blue-700 border-blue-100',
                                str_contains($examSlug, 'neco') => 'bg-amber-50 text-amber-800 border-amber-100',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <div class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/60 rounded-xl px-2 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-800 font-black text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                                    {{ strtoupper(substr($sess->user->name ?? 'C', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-xs sm:text-sm text-slate-900 truncate">
                                            {{ $sess->user->name ?? 'Anonymous Student' }}
                                        </h4>
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider border {{ $badgeColor }}">
                                            {{ $sess->exam->slug ?? $sess->exam->name }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                        <span class="font-bold text-slate-700 capitalize">{{ $sess->mode }} Mode</span> &bull;
                                        <span class="text-slate-600">{{ $sess->correct_count }} of {{ $sess->total_questions }} correct</span>
                                        @php
                                            $subjectNames = collect($sess->subjects)->map(fn($id) => $subjectsMap[$id] ?? null)->filter()->take(2)->implode(', ');
                                        @endphp
                                        @if($subjectNames)
                                            <span class="text-slate-400">({{ $subjectNames }})</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-xs font-black font-heading text-slate-900 block">
                                    {{ round($sess->score) }}{{ str_contains($examSlug, 'utme') ? '/400' : '%' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ $sess->submitted_at?->diffForHumans() ?? 'just now' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>No candidate test submissions recorded yet.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Operational Diagnostics & Seeder Health (5 Cols) -->
            <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-black text-slate-900 font-heading">System Diagnostics</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Integrations and infrastructure health status</p>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 rounded-full text-[10px] font-black uppercase tracking-wider">
                            All Systems Go
                        </span>
                    </div>

                    <!-- Diagnostics List -->
                    <div class="space-y-3 text-xs">
                        
                        <!-- ALOC API Seeder -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xs">
                                    API
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-900">ALOC Station Engine</div>
                                    <div class="text-[11px] text-slate-400">dev.aloc.com.ng/api/v1 active</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.bulk-seeder') }}" class="px-2.5 py-1 bg-white border border-slate-200 hover:border-emerald-500 text-emerald-700 rounded-lg text-[10px] font-black transition-colors">
                                Launch
                            </a>
                        </div>

                        <!-- Database Questions Health -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-black text-xs">
                                    DB
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-900">Question Integrity</div>
                                    <div class="text-[11px] text-slate-400">{{ number_format($totalQuestions) }} rows indexed</div>
                                </div>
                            </div>
                            <span class="text-xs font-black text-emerald-600">Operational</span>
                        </div>

                        <!-- Cache State -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs">
                                    OP
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-900">Application Cache</div>
                                    <div class="text-[11px] text-slate-400">Database/file driver synced</div>
                                </div>
                            </div>
                            <button wire:click="clearSystemCache" class="px-2.5 py-1 bg-white border border-slate-200 hover:border-amber-500 text-amber-800 rounded-lg text-[10px] font-black transition-colors">
                                Purge
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Fast Seeding Callout Card -->
                <div class="p-4 bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl text-white shadow-md flex items-center justify-between gap-3">
                    <div>
                        <h4 class="font-black text-xs sm:text-sm">Need More Past Questions?</h4>
                        <p class="text-[11px] text-emerald-100 mt-0.5">Automate extraction of 15 authentic past questions per batch.</p>
                    </div>
                    <a href="{{ route('admin.bulk-seeder') }}" class="px-3.5 py-2 bg-white text-emerald-800 hover:bg-emerald-50 rounded-xl text-xs font-black whitespace-nowrap shadow-sm transition-all flex-shrink-0">
                        Open Seeder &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- Section 5: Question Bank Seeding Coverage Matrix -->
        <div class="bg-white border border-slate-200/80 p-6 rounded-3xl shadow-sm"
             x-data="{ activeTab: 'all', search: '' }">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900 font-heading">Question Repository Matrix</h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Inventory status of locally seeded curriculum across UTME, WAEC SSCE, and NECO
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Live Search Filter -->
                    <div class="relative">
                        <input type="text" 
                               x-model="search" 
                               placeholder="Filter subject..." 
                               class="text-xs py-1.5 pl-8 pr-3 border border-slate-200 rounded-xl focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 w-44 sm:w-52 transition-all">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <!-- Exam Board Filter Tabs -->
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold">
                        <button type="button" 
                                @click="activeTab = 'all'" 
                                :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1 rounded-lg transition-all">
                            All ({{ count($questionCoverage) }})
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
                        + Seed Questions
                    </a>
                </div>
            </div>

            <!-- Subject Cards Grid with Tab & Search filtering -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[520px] overflow-y-auto pr-1">
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
                         class="p-4 border border-slate-100 hover:border-slate-300 bg-white hover:bg-slate-50/50 rounded-2xl transition-all shadow-xs flex flex-col justify-between">
                        
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-black text-slate-800 truncate pr-2">{{ $subject->name }}</span>
                                <span class="text-xs font-black {{ $subject->questions_count > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                    {{ number_format($subject->questions_count) }} Qs
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between text-[10px] text-slate-400 uppercase font-bold mb-2.5">
                                <span>{{ $subject->exam->name }}</span>
                                <span class="{{ $subject->questions_count >= 100 ? 'text-emerald-600' : ($subject->questions_count > 0 ? 'text-amber-600' : 'text-rose-500') }}">
                                    {{ $subject->questions_count >= 100 ? 'Well Seeded' : ($subject->questions_count > 0 ? 'Growing' : 'Needs Seeding') }}
                                </span>
                            </div>
                            
                            <!-- Progress visual -->
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mb-3">
                                @php
                                    $percent = min(100, ($subject->questions_count / 150) * 100);
                                    $barColor = $subject->questions_count >= 100 ? 'bg-emerald-500' : ($subject->questions_count > 0 ? 'bg-amber-500' : 'bg-rose-400');
                                @endphp
                                <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ max(4, $percent) }}%"></div>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                            <a href="{{ route('admin.questions', ['subject_id' => $subject->id]) }}" class="text-slate-500 hover:text-slate-900 font-bold">
                                View Questions
                            </a>
                            @if($subject->questions_count == 0)
                                <a href="{{ route('admin.bulk-seeder') }}" class="font-black text-emerald-600 hover:text-emerald-700 flex items-center gap-0.5">
                                    <span>Seed</span>
                                    <span>&rarr;</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-16 text-center text-xs text-slate-400">
                        No subject records found.
                    </div>
                @endforelse
            </div>
        </div>

    </main>
</div>

<!-- Load Chart.js CDN for Revenue Telemetry -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

