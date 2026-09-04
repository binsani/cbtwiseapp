<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <aside class="w-full lg:w-64 bg-white border-r border-slate-100 flex-shrink-0 p-6 space-y-8 font-sans">
        <div>
            <p class="text-xs font-black uppercase tracking-wider text-slate-400 mb-4">Admin Panel</p>
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.questions') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Questions
                </a>
                <a href="{{ route('admin.exams-subjects') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Exams & Subjects
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Subscriptions
                </a>
                <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 font-extrabold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14"/></svg>
                    Analytics
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2"/></svg>
                    Messages
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Reports
                </a>
                <a href="{{ route('admin.purchase-codes') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5-3a2 2 0 00-2 2v7a2 2 0 002 2h5a2 2 0 002-2V9a2 2 0 00-2-2h-5z"/></svg>
                    Purchase Codes
                </a>
            </nav>
        </div>

        <div class="border-t border-slate-100 pt-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to App
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Analytics</h1>
                <p class="text-xs text-slate-500 mt-0.5">Platform usage analytics</p>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Attempts</p>
                <h3 class="text-3xl font-black text-slate-950 font-heading leading-tight">{{ $totalAttempts }}</h3>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Avg Score</p>
                <h3 class="text-3xl font-black text-slate-950 font-heading leading-tight">{{ $avgScore }}%</h3>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Exams Tracked</p>
                <h3 class="text-3xl font-black text-slate-950 font-heading leading-tight">{{ $examsTracked }}</h3>
            </div>
        </div>

        <!-- Row 1 Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Daily Attempts Chart -->
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 font-heading mb-6">Daily Attempts (Last 14 Days)</h4>
                <div class="h-64 relative">
                    <canvas id="dailyAttemptsChart"></canvas>
                </div>
            </div>

            <!-- Attempts by Exam Pie -->
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 font-heading mb-6">Attempts by Exam</h4>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="attemptsByExamChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Row 2 Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top Subjects Horizontal Bar -->
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 font-heading mb-6">Top Subjects</h4>
                <div class="h-64 relative">
                    <canvas id="topSubjectsChart"></canvas>
                </div>
            </div>

            <!-- Practice vs Mock Mode Pie -->
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 font-heading mb-6">Practice vs Mock</h4>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="practiceVsMockChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart JS initialization -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:navigated', () => {
                initializeCharts();
            });

            // Fallback load
            window.addEventListener('load', () => {
                if (typeof Chart !== 'undefined') {
                    initializeCharts();
                }
            });

            function initializeCharts() {
                // 1. Daily attempts
                const dailyCtx = document.getElementById('dailyAttemptsChart');
                if (dailyCtx) {
                    new Chart(dailyCtx, {
                        type: 'line',
                        data: {
                            labels: @json($dailyAttemptsLabels),
                            datasets: [{
                                label: 'Attempts',
                                data: @json($dailyAttemptsValues),
                                borderColor: '#0f766e', // Emerald 700
                                backgroundColor: 'rgba(15, 118, 110, 0.05)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.35,
                                pointBackgroundColor: '#0f766e',
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }

                // 2. Attempts by Exam
                const examCtx = document.getElementById('attemptsByExamChart');
                if (examCtx) {
                    new Chart(examCtx, {
                        type: 'pie',
                        data: {
                            labels: @json($attemptsByExamLabels).map((label, idx) => `${label} (${@json($attemptsByExamValues)[idx]})`),
                            datasets: [{
                                data: @json($attemptsByExamValues),
                                backgroundColor: ['#0f766e', '#2563eb', '#9333ea', '#ea580c'],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'left',
                                    labels: { font: { weight: 'bold', size: 11 } }
                                }
                            }
                        }
                    });
                }

                // 3. Practice vs Mock
                const modeCtx = document.getElementById('practiceVsMockChart');
                if (modeCtx) {
                    new Chart(modeCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Mock', 'Practice'].map((label, idx) => `${label} (${@json($practiceVsMockValues)[idx]})`),
                            datasets: [{
                                data: @json($practiceVsMockValues),
                                backgroundColor: ['#0f766e', '#2563eb'],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: { font: { weight: 'bold', size: 11 } }
                                }
                            }
                        }
                    });
                }

                // 4. Top Subjects
                const subjectCtx = document.getElementById('topSubjectsChart');
                if (subjectCtx) {
                    new Chart(subjectCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($topSubjectsLabels),
                            datasets: [{
                                data: @json($topSubjectsValues),
                                backgroundColor: '#2563eb',
                                borderRadius: 8,
                                barThickness: 16
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' } },
                                y: { grid: { display: false } }
                            }
                        }
                    });
                }
            }
        </script>
    </div>
</div>
