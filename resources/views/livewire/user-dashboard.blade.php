<div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 space-y-8"
     x-data="{
         status: @entangle('studyPlanStatus'),
         initChart() {
             const data = @json($subjectPerformance);
             const labels = Object.keys(data);
             const values = Object.values(data);

             if (labels.length === 0) return;

             const ctx = document.getElementById('subjectChart');
             if (!ctx) return;

             new Chart(ctx, {
                 type: 'bar',
                 data: {
                     labels: labels,
                     datasets: [{
                         label: 'Accuracy %',
                         data: values,
                         backgroundColor: values.map(v => v >= 75 ? 'rgba(16, 185, 129, 0.8)' : (v >= 50 ? 'rgba(245, 158, 11, 0.8)' : 'rgba(239, 68, 68, 0.8)')),
                         borderRadius: 8,
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
                             ticks: { callback: v => v + '%' },
                             grid: { color: 'rgba(241, 245, 249, 1)' }
                         },
                         x: { grid: { display: false } }
                     },
                     plugins: {
                         legend: { display: false }
                     }
                 }
             });
         }
     }"
     x-init="setTimeout(() => initChart(), 150)">

    <!-- Navigation Tabs -->
    <x-dashboard-nav />

    <!-- In-Progress Active Session Banner -->
    @if($activeSession)
        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 rounded-3xl p-6 text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-pulse">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-2xl flex-shrink-0">
                    ⏱️
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-[10px] font-black uppercase tracking-wider">In Progress</span>
                        <span class="text-xs opacity-90">Started {{ $activeSession->started_at?->diffForHumans() ?? 'recently' }}</span>
                    </div>
                    <h3 class="text-xl font-black font-heading mt-0.5">{{ $activeSession->exam->name }} Session</h3>
                    <p class="text-xs opacity-90 mt-0.5">{{ $activeSession->total_questions }} Questions • {{ ucfirst($activeSession->mode) }} Mode</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="cancelActiveSession" 
                        wire:confirm="Are you sure you want to discard this in-progress exam?"
                        class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-extrabold transition-all">
                    Discard
                </button>
                <a href="{{ route('exam.run', $activeSession->id) }}" 
                   class="px-6 py-2.5 bg-white text-orange-600 hover:bg-orange-50 rounded-xl text-xs font-black shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                    <span>Resume Exam</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    @endif

    <!-- Alert Messages -->
    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-2xl text-sm font-semibold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-2xl text-sm font-semibold shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Motivational Quote Banner -->
    <div class="bg-amber-50/60 border border-amber-200/70 rounded-2xl p-4 flex items-center gap-3 text-amber-900 text-xs shadow-sm">
        <div class="w-7 h-7 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700 flex-shrink-0 text-base">
            💡
        </div>
        <p class="font-semibold italic">
            "Success is the sum of small efforts, repeated day in and day out." — Robert Collier
        </p>
    </div>

    <!-- 4 High-Impact Summary Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tests Taken -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-500">Tests Taken</span>
            <div class="mt-2">
                <span class="text-3xl font-black text-slate-900 font-heading">{{ $totalTestsTaken }}</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-2">Completed CBTs</span>
        </div>

        <!-- Avg. Score % -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-500">Avg. Score</span>
            <div class="mt-2">
                <span class="text-3xl font-black text-emerald-600 font-heading">{{ $accuracy }}%</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-2">Overall Accuracy</span>
        </div>

        <!-- Study Streak -->
        <a href="{{ route('dashboard.streak') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-amber-400 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Study Streak</span>
                <span class="text-sm">🔥</span>
            </div>
            <div class="mt-2">
                <span class="text-3xl font-black text-amber-600 font-heading">{{ $streakDays }} days</span>
            </div>
            <span class="text-[11px] text-slate-400 group-hover:text-amber-600 transition-colors mt-2">Keep it burning &rarr;</span>
        </a>

        <!-- Time Spent -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-500">Time Spent</span>
            <div class="mt-2">
                <span class="text-3xl font-black text-slate-900 font-heading">{{ $totalTimeSpentHours }} hrs</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-2">Practice sessions</span>
        </div>
    </div>

    <!-- Free Plan Usage Banner (Shown for Free Tier Users) -->
    @if(!$isPremium)
        <div class="bg-amber-50/50 border border-amber-200/80 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 bg-amber-200/80 text-amber-900 rounded-lg text-xs font-black uppercase tracking-wider">Free Plan</span>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-700 font-bold">
                    <span>Daily Questions: <strong class="text-slate-900 font-black">{{ $dailyQuestionsUsed }} / {{ $dailyGoal }}</strong></span>
                    <span class="text-slate-300 hidden sm:inline">&bull;</span>
                    <span>Monthly Mock Exams: <strong class="text-slate-900 font-black">{{ $monthlyMockUsed }} / 3</strong></span>
                </div>
            </div>
            <a href="{{ route('pricing') }}" 
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-xs font-black shadow-sm transition-all whitespace-nowrap self-start sm:self-center">
                <span>⚡ Upgrade</span>
                <span>&rarr;</span>
            </a>
        </div>
    @endif

    <!-- Primary Action CTAs -->
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('exam.setup') }}" 
           class="inline-flex items-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-black rounded-2xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-lg">
            <span>Continue Practicing</span>
            <span>&rarr;</span>
        </a>

        <a href="{{ route('topic.practice') }}" 
           class="inline-flex items-center gap-2 px-5 py-3.5 bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white text-sm font-black rounded-2xl transition-all shadow-md shadow-purple-600/10 hover:shadow-lg">
            <span>Practice by Topic</span>
            <span>🎯</span>
        </a>

        <a href="{{ route('mock-exams') }}" 
           class="inline-flex items-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-black rounded-2xl transition-all shadow-md shadow-blue-600/10 hover:shadow-lg">
            <span>Take Mock Exam</span>
            <span>⚡</span>
        </a>
    </div>

    <!-- Diagnostic Weak Area Alert Banner (If Data Exists) -->
    @if($weakestSubject && $weakestSubject['accuracy'] < 60)
        <div class="bg-gradient-to-r from-rose-50 via-amber-50 to-emerald-50 border border-rose-200/80 rounded-3xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl font-bold flex-shrink-0">
                    🎯
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-800 px-2 py-0.5 rounded-full">Diagnostic Insight</span>
                        <span class="text-xs text-slate-500">Target your lowest scoring subject</span>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mt-1">
                        {{ $weakestSubject['name'] }} accuracy is {{ $weakestSubject['accuracy'] }}%
                    </h4>
                    <p class="text-xs text-slate-600 mt-0.5">Focusing on this subject can immediately raise your aggregate CBT score.</p>
                </div>
            </div>
            <a href="{{ route('exam.setup', ['subject' => $weakestSubject['id']]) }}" 
               class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black rounded-xl transition-all shadow-md flex items-center gap-1.5 whitespace-nowrap self-start md:self-center">
                <span>Practice {{ $weakestSubject['name'] }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    @endif

    <!-- Quick CBT Launch Hub -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-900 font-heading">Quick CBT Launcher</h3>
                <p class="text-xs text-slate-500 mt-0.5">Select an examination body to configure and launch real past questions</p>
            </div>
            <a href="{{ route('exam.setup') }}" class="text-xs font-extrabold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Full Setup</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- UTME Tile -->
            <a href="{{ route('exam.setup', ['exam' => 'utme']) }}" 
               class="group p-5 rounded-2xl border border-slate-200/80 hover:border-emerald-500 bg-gradient-to-b from-white to-slate-50 hover:to-emerald-50/30 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-2xl block mb-2">🇳🇬</span>
                    <h4 class="font-black text-slate-900 text-sm font-heading group-hover:text-emerald-700">JAMB UTME</h4>
                    <p class="text-[11px] text-slate-500 mt-1">400 Marks • 4 Subjects format with English compulsory</p>
                </div>
                <div class="mt-4 text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <span>Launch UTME</span>
                    <span>&rarr;</span>
                </div>
            </a>

            <!-- WAEC Tile -->
            <a href="{{ route('exam.setup', ['exam' => 'waec']) }}" 
               class="group p-5 rounded-2xl border border-slate-200/80 hover:border-emerald-500 bg-gradient-to-b from-white to-slate-50 hover:to-emerald-50/30 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-2xl block mb-2">📚</span>
                    <h4 class="font-black text-slate-900 text-sm font-heading group-hover:text-emerald-700">WAEC SSCE</h4>
                    <p class="text-[11px] text-slate-500 mt-1">Senior Certificate simulations with detailed explanations</p>
                </div>
                <div class="mt-4 text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <span>Launch WAEC</span>
                    <span>&rarr;</span>
                </div>
            </a>

            <!-- NECO Tile -->
            <a href="{{ route('exam.setup', ['exam' => 'neco']) }}" 
               class="group p-5 rounded-2xl border border-slate-200/80 hover:border-emerald-500 bg-gradient-to-b from-white to-slate-50 hover:to-emerald-50/30 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-2xl block mb-2">🎓</span>
                    <h4 class="font-black text-slate-900 text-sm font-heading group-hover:text-emerald-700">NECO SSCE</h4>
                    <p class="text-[11px] text-slate-500 mt-1">National Examinations Council past questions archive</p>
                </div>
                <div class="mt-4 text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <span>Launch NECO</span>
                    <span>&rarr;</span>
                </div>
            </a>

            <!-- JAMB Brochure Checker Tile -->
            <a href="{{ route('jamb.checker') }}" 
               class="group p-5 rounded-2xl border border-emerald-200 hover:border-emerald-500 bg-gradient-to-b from-white to-emerald-50/40 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-2xl block mb-2">📖</span>
                    <h4 class="font-black text-slate-900 text-sm font-heading group-hover:text-emerald-700">Course Checker</h4>
                    <p class="text-[11px] text-slate-500 mt-1">JAMB brochure subject combinations & O'Level prerequisites</p>
                </div>
                <div class="mt-4 text-xs font-bold text-emerald-700 flex items-center gap-1">
                    <span>Check Courses</span>
                    <span>&rarr;</span>
                </div>
            </a>

            <!-- Mock Exam Tile -->
            <a href="{{ route('exam.setup', ['mode' => 'mock']) }}" 
               class="group p-5 rounded-2xl border border-amber-200 hover:border-amber-400 bg-gradient-to-b from-white to-amber-50/40 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-2xl block mb-2">⚡</span>
                    <h4 class="font-black text-slate-900 text-sm font-heading group-hover:text-amber-700">Timed Mock</h4>
                    <p class="text-[11px] text-slate-500 mt-1">Full-length timed simulation under authentic exam pressure</p>
                </div>
                <div class="mt-4 text-xs font-bold text-amber-700 flex items-center gap-1">
                    <span>Start Mock</span>
                    <span>&rarr;</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Grid Content: Left 2 Cols (Chart & History) | Right 1 Col (AI Coach & Referrals) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Performance Chart Card -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 font-heading">Subject Accuracy Breakdown</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Green (&ge;75%), Amber (50-74%), Red (&lt;50%)</p>
                    </div>
                    <a href="{{ route('dashboard.performance') }}" class="text-xs font-extrabold text-emerald-600 hover:text-emerald-700">
                        View Trends &rarr;
                    </a>
                </div>
                <div class="relative h-64">
                    @if (empty($subjectPerformance))
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 text-xs">
                            <span class="text-3xl mb-2">📊</span>
                            <span>Complete practice exams to unlock your subject accuracy breakdown.</span>
                        </div>
                    @else
                        <canvas id="subjectChart"></canvas>
                    @endif
                </div>
            </div>

            <!-- Recent Sessions Card -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 font-heading">Recent CBT Sessions</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Your recent test records and score reports</p>
                    </div>
                    <a href="{{ route('dashboard.history') }}" class="text-xs font-extrabold text-emerald-600 hover:text-emerald-700">
                        View All ({{ count($recentSessions) }}) &rarr;
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentSessions as $sess)
                        <div class="py-4 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $sess->exam->name }}</h4>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full uppercase font-extrabold bg-slate-100 text-slate-600">
                                        {{ $sess->mode }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    Submitted {{ $sess->submitted_at?->diffForHumans() ?? 'recently' }} • {{ $sess->correct_count }} / {{ $sess->total_questions }} correct
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-black text-slate-900 font-heading">{{ round($sess->score) }}{{ $sess->exam->slug === 'utme' ? '/400' : '%' }}</span>
                                <a href="{{ route('dashboard.session-review', $sess->id) }}" 
                                   class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold rounded-xl transition-all">
                                    Review
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            <span class="text-3xl block mb-2">📝</span>
                            You haven't completed any practice sessions yet. Start one now!
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('exam.setup') }}" 
                       class="w-full flex items-center justify-center py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-2xl transition-all shadow-md shadow-emerald-600/10">
                        Start New Exam Session &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Col: Focus Areas, Recommendation, AI Coach & Referral Sharing -->
        <div class="space-y-6">
            <!-- Focus Areas Card -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900 font-heading">Focus Areas</h3>
                        <p class="text-xs text-slate-500">Subjects needing your attention</p>
                    </div>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                        {{ count($subjectPerformance) }} tracked
                    </span>
                </div>

                @if(!empty($subjectPerformance))
                    <div class="space-y-3 pt-2">
                        @foreach(array_slice($subjectPerformance, 0, 3, true) as $subjName => $acc)
                            <div>
                                <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-1">
                                    <span class="truncate max-w-[180px]">{{ $subjName }}</span>
                                    <span class="{{ $acc >= 75 ? 'text-emerald-600' : ($acc >= 50 ? 'text-amber-600' : 'text-rose-600') }}">{{ $acc }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all {{ $acc >= 75 ? 'bg-emerald-500' : ($acc >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                         style="width: {{ $acc }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 py-4 text-center">
                        Take practice exams to reveal your weak subjects and focus areas.
                    </p>
                @endif
            </div>

            <!-- Recommendation Card -->
            @if($weakestSubject)
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-lg space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full">Recommendation</span>
                    </div>
                    <h4 class="text-lg font-black font-heading leading-snug">
                        Focus on {{ $weakestSubject['name'] }}
                    </h4>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Your current accuracy is {{ $weakestSubject['accuracy'] }}%. Practicing 20 questions in {{ $weakestSubject['name'] }} today can lift your score by up to 15 points.
                    </p>
                    <a href="{{ route('exam.setup', ['subject' => $weakestSubject['id']]) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-emerald-800 hover:bg-emerald-50 rounded-xl text-xs font-black shadow-md transition-all">
                        <span>Practice {{ $weakestSubject['name'] }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            @endif

            <!-- AI Study Coach Card -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center space-x-3">
                    <div class="bg-emerald-50 p-2.5 rounded-2xl text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 font-heading">AI Study Coach</h3>
                        <p class="text-xs text-slate-500">Personalized 7-day study plan</p>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4" 
                     wire:poll.3s="checkStudyPlanStatus" 
                     x-show="status === 'generating'">
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-emerald-500 border-t-transparent mb-3"></div>
                        <p class="text-xs font-bold text-slate-700">Analyzing your performance patterns...</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Generating targeted recommendations.</p>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4" x-show="status !== 'generating'">
                    @if ($studyPlan)
                        <div class="prose prose-slate prose-sm max-w-none text-slate-600 text-xs overflow-y-auto max-h-80">
                            {!! str($studyPlan)->markdown() !!}
                        </div>
                        
                        <button wire:click="generateStudyPlan"
                                class="w-full mt-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                            Regenerate Plan
                        </button>
                    @else
                        <div class="text-center py-4 text-slate-500">
                            <p class="text-xs text-slate-600">Get an AI-tailored study schedule based on your strengths and weak subjects.</p>
                            
                            <button wire:click="generateStudyPlan"
                                    class="w-full mt-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition-all shadow-md">
                                Generate My Study Plan
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Referral Quick Box -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-black font-heading flex items-center gap-1.5">
                        <span>🤝</span>
                        <span>Refer & Earn</span>
                    </h4>
                    <a href="{{ route('dashboard.referrals') }}" class="text-[11px] text-emerald-400 font-bold hover:underline">
                        Details &rarr;
                    </a>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Share your code with friends. They save N100 and you earn free days when they upgrade.
                </p>
                <div class="bg-white/10 rounded-2xl p-3 flex items-center justify-between border border-white/10">
                    <span class="text-lg font-black font-mono tracking-wider select-all">{{ $referralCode }}</span>
                    <button type="button"
                            onclick="navigator.clipboard.writeText('{{ $referralCode }}'); alert('Referral code copied!');"
                            class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black rounded-lg transition-colors">
                        Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
