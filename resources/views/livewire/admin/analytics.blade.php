<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

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
