<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-8 font-sans">
    
    <!-- Navigation Tabs -->
    <x-dashboard-nav />

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-heading">Performance Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Detailed statistical insights into your exam preparation progress.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-2xl transition-all shadow-md">
            &larr; Back to Dashboard
        </a>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Overall Accuracy</div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 font-heading">{{ $overallAccuracy }}%</span>
            </div>
            <div class="text-slate-400 text-xs mt-2">Across all practiced subjects</div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Practice Sessions</div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 font-heading">{{ $totalSessions }}</span>
            </div>
            <div class="text-slate-400 text-xs mt-2">Completed CBT simulations</div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Strongest Subject</div>
            <div class="mt-4">
                <span class="text-xl font-black text-emerald-600 font-heading block truncate">
                    {{ count($subjectAccuracy) > 0 ? $subjectAccuracy[0]['subject'] : 'N/A' }}
                </span>
                <span class="text-xs text-slate-400 font-semibold block mt-1">
                    {{ count($subjectAccuracy) > 0 ? $subjectAccuracy[0]['accuracy'] . '% accuracy' : '' }}
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Weakest Subject</div>
            <div class="mt-4">
                <span class="text-xl font-black text-red-600 font-heading block truncate">
                    {{ count($subjectAccuracy) > 1 ? end($subjectAccuracy)['subject'] : 'N/A' }}
                </span>
                <span class="text-xs text-slate-400 font-semibold block mt-1">
                    {{ count($subjectAccuracy) > 1 ? end($subjectAccuracy)['accuracy'] . '% accuracy' : '' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Graphics and Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Subject Accuracy Table -->
        <div class="lg:col-span-1 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-50 pb-2">Subject Accuracy</h3>
            
            <div class="space-y-4">
                @forelse($subjectAccuracy as $item)
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                            <span>{{ $item['subject'] }}</span>
                            <span>{{ $item['accuracy'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div 
                                class="h-full rounded-full transition-all duration-500 {{ $item['accuracy'] >= 75 ? 'bg-emerald-500' : ($item['accuracy'] >= 50 ? 'bg-amber-500' : 'bg-red-500') }}"
                                style="width: {{ $item['accuracy'] }}%"
                            ></div>
                        </div>
                        <div class="text-[10px] text-slate-400 font-semibold">
                            {{ $item['correct'] }} / {{ $item['total'] }} questions correct
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-sm py-6 text-center">No subject stats yet. Start practicing!</p>
                @endforelse
            </div>
        </div>

        <!-- Trend Graph -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <h3 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-50 pb-2 mb-4">Accuracy Over Time (Trend)</h3>
            
            <div class="relative flex-1 min-h-[300px]" x-data="{
                init() {
                    const ctx = document.getElementById('trendChart');
                    if (!ctx) return;
                    
                    const labels = {{ json_encode(collect($dailyTrend)->pluck('date')) }};
                    const dataPoints = {{ json_encode(collect($dailyTrend)->pluck('accuracy')) }};

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Daily Accuracy (%)',
                                data: dataPoints,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointBackgroundColor: '#10b981'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: function(value) { return value + '%'; }
                                    }
                                }
                            }
                        }
                    });
                }
            }">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Include Chart.js script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
