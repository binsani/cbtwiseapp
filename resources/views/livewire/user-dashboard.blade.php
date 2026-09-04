<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8"
     x-data="{
         status: @entangle('studyPlanStatus'),
         initChart() {
             const data = @json($subjectPerformance);
             const labels = Object.keys(data);
             const values = Object.values(data);

             if (labels.length === 0) return;

             new Chart(document.getElementById('subjectChart'), {
                 type: 'bar',
                 data: {
                     labels: labels,
                     datasets: [{
                         label: 'Accuracy %',
                         data: values,
                         backgroundColor: 'rgba(16, 185, 129, 0.2)',
                         borderColor: 'rgba(16, 185, 129, 1)',
                         borderWidth: 2,
                         borderRadius: 12,
                         borderSkipped: false,
                     }]
                 },
                 options: {
                     responsive: true,
                     maintainAspectRatio: false,
                     scales: {
                         y: {
                             beginAtZero: true,
                             max: 100,
                             grid: {
                                 color: 'rgba(241, 245, 249, 1)'
                             }
                         },
                         x: {
                             grid: {
                                 display: false
                             }
                         }
                     },
                     plugins: {
                         legend: {
                             display: false
                         }
                     }
                 }
             });
         }
     }"
     x-init="setTimeout(() => initChart(), 100)">

    <!-- PWA Install Banner -->
    @if(count($recentSessions) >= 3)
    <div x-data="{ showInstallBanner: false }"
         x-init="
            if (window.deferredPrompt) {
                showInstallBanner = true;
            }
            window.addEventListener('pwa-installable', () => {
                showInstallBanner = true;
            });
         }"
         x-show="showInstallBanner"
         class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-6 text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h4 class="text-lg font-bold font-heading">Install CBTWise Mobile App</h4>
            <p class="text-sm opacity-90">Access your practice offline, view metrics, and learn faster on the go!</p>
        </div>
        <button @click="
            if (window.deferredPrompt) {
                window.deferredPrompt.prompt();
                window.deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        showInstallBanner = false;
                    }
                    window.deferredPrompt = null;
                });
            }
        " class="bg-white hover:bg-slate-50 text-emerald-600 font-extrabold text-sm px-6 py-3 rounded-2xl transition-all shadow-md whitespace-nowrap">
            Install Now
        </button>
    </div>
    @endif

    <!-- Upper Stats Banner -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Streak Card -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-15">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-sm font-bold uppercase tracking-wider opacity-95">Study Streak</p>
            <h3 class="text-4xl font-black font-heading mt-2">{{ $streakDays }} Days</h3>
            <p class="text-xs mt-2 opacity-90">Keep the fire burning! Practice daily.</p>
        </div>

        <!-- Answered Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xl relative overflow-hidden">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Questions Solved</p>
            <h3 class="text-4xl font-black text-slate-800 font-heading mt-2">{{ $totalAnswered }}</h3>
            <p class="text-xs text-slate-500 mt-2">Total questions answered across sessions.</p>
        </div>

        <!-- Accuracy Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xl relative overflow-hidden">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Overall Accuracy</p>
            <h3 class="text-4xl font-black text-emerald-600 font-heading mt-2">{{ $accuracy }}%</h3>
            <div class="w-full bg-slate-100 h-2 rounded-full mt-3 overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $accuracy }}%"></div>
            </div>
        </div>

        <!-- Subscription/Status Card -->
        <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
            <p class="text-sm font-bold text-emerald-400 uppercase tracking-wider">Membership Status</p>
            @if (Auth::user()->isPremium())
                <h3 class="text-2xl font-black font-heading mt-2 text-white">Premium Access</h3>
                <p class="text-xs text-slate-400 mt-2">Expires: {{ Auth::user()->premium_expires_at?->format('d M, Y') ?? 'Never' }}</p>
            @else
                <h3 class="text-2xl font-black font-heading mt-2 text-white">Free Plan</h3>
                <a href="{{ route('pricing') }}" class="inline-block mt-3 text-xs font-bold bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl transition-colors">
                    Upgrade to Premium
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl text-sm font-semibold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl text-sm font-semibold shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Chart and Recent Sessions -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Performance Chart Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xl">
                <h3 class="text-xl font-bold text-slate-800 font-heading mb-4">Subject Performance Analysis</h3>
                <div class="relative h-64">
                    @if (empty($subjectPerformance))
                        <div class="absolute inset-0 flex items-center justify-center text-slate-400 text-sm">
                            Complete practice exams to see subject breakdowns here.
                        </div>
                    @else
                        <canvas id="subjectChart"></canvas>
                    @endif
                </div>
            </div>

            <!-- Recent Sessions Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-800 font-heading">Recent Sessions</h3>
                    <a href="{{ route('dashboard.history') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentSessions as $sess)
                        <div class="py-4 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $sess->exam->name }} Session</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Submitted: {{ $sess->submitted_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-lg font-black text-slate-700">{{ $sess->score }}%</span>
                                <a href="{{ route('exam.results', $sess->id) }}" 
                                   class="px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                                    Review
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-sm">
                            You haven't completed any practice sessions yet.
                        </div>
                    @endforelse
                </div>
                <div class="mt-6">
                    <a href="{{ route('exam.setup') }}" 
                       class="w-full flex items-center justify-center py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl transition-colors shadow-lg shadow-emerald-600/10">
                        Start New Exam Session
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Col: AI Study Coach -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xl h-fit space-y-6">
            <div class="flex items-center space-x-3">
                <div class="bg-emerald-50 p-2.5 rounded-2xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 font-heading">AI Study Coach</h3>
                    <p class="text-xs text-slate-500">Your personalized learning path</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4" 
                 wire:poll.3s="checkStudyPlanStatus" 
                 x-show="status === 'generating'">
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-4 border-emerald-500 border-t-transparent mb-4"></div>
                    <p class="text-sm font-semibold text-slate-600">Analyzing your performance...</p>
                    <p class="text-xs text-slate-400 mt-1">Generating your study plan.</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4" x-show="status !== 'generating'">
                @if ($studyPlan)
                    <div class="prose prose-slate prose-sm max-w-none text-slate-600 text-sm overflow-y-auto max-h-96">
                        {!! str($studyPlan)->markdown() !!}
                    </div>
                    
                    <button wire:click="generateStudyPlan"
                            class="w-full mt-6 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Re-Generate Study Plan
                    </button>
                @else
                    <div class="text-center py-6 text-slate-500">
                        <p class="text-sm">No active study plan generated yet.</p>
                        <p class="text-xs text-slate-400 mt-2">Generate a plan to target your weak subjects in a structured 7-day schedule.</p>
                        
                        <button wire:click="generateStudyPlan"
                                class="w-full mt-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold rounded-2xl transition-colors shadow-md">
                            Generate My Study Plan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
