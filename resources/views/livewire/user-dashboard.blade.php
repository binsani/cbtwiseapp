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

    <!-- Daily Goal & High-Impact Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Daily Goal Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Daily Practice Goal</span>
                    <span class="text-xs font-extrabold {{ $todayAnswered >= $dailyGoal ? 'text-emerald-600 bg-emerald-50' : 'text-slate-600 bg-slate-100' }} px-2 py-0.5 rounded-full">
                        {{ $todayAnswered }} / {{ $dailyGoal }}
                    </span>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        @php
                            $goalPercent = min(100, $dailyGoal > 0 ? round(($todayAnswered / $dailyGoal) * 100) : 0);
                        @endphp
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-full rounded-full transition-all duration-500" 
                             style="width: {{ $goalPercent }}%"></div>
                    </div>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">
                @if($todayAnswered >= $dailyGoal)
                    <span class="text-emerald-600 font-bold">🎉 Daily goal achieved!</span> Keep it going.
                @else
                    <span>{{ max(0, $dailyGoal - $todayAnswered) }} more questions to hit today's target.</span>
                @endif
            </p>
        </div>

        <!-- Study Streak Card -->
        <a href="{{ route('dashboard.streak') }}" class="group bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden flex flex-col justify-between hover:shadow-orange-500/20 transition-all">
            <div class="absolute -right-4 -bottom-4 opacity-15">
                <svg class="w-28 h-28" fill="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            </div>
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider opacity-90 flex items-center justify-between">
                    <span>Study Streak</span>
                    <span>🔥</span>
                </span>
                <h3 class="text-3xl font-black font-heading mt-2">{{ $streakDays }} Days</h3>
            </div>
            <p class="text-xs opacity-90 mt-3 group-hover:underline flex items-center gap-1">
                <span>View milestones & freezes</span>
                <span>&rarr;</span>
            </p>
        </a>

        <!-- Accuracy & Solved Card -->
        <a href="{{ route('dashboard.performance') }}" class="group bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between hover:border-emerald-200 transition-all">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Overall Accuracy</span>
                    <span class="text-xs font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        {{ $totalAnswered }} Solved
                    </span>
                </div>
                <h3 class="text-3xl font-black text-slate-900 font-heading mt-2">{{ $accuracy }}%</h3>
                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $accuracy }}%"></div>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 group-hover:text-emerald-600 flex items-center gap-1 font-semibold">
                <span>Subject performance breakdown</span>
                <span>&rarr;</span>
            </p>
        </a>

        <!-- Leaderboard & Bookmarks Quick Card -->
        <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center justify-between">
                    <span>Scholar Rank</span>
                    <span>🏆</span>
                </span>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-black font-heading">
                        {{ $leaderboardRank ? '#' . $leaderboardRank : 'Unranked' }}
                    </h3>
                    <span class="text-xs text-slate-400">Nationwide</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 text-xs text-slate-300">
                <a href="{{ route('dashboard.bookmarks') }}" class="hover:text-white flex items-center gap-1 font-bold">
                    <span>🔖 {{ $bookmarkCount }} Bookmarks</span>
                </a>
                <a href="{{ route('dashboard.leaderboard') }}" class="hover:text-emerald-400 font-bold">
                    View Board &rarr;
                </a>
            </div>
        </div>
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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

            <!-- Mock Exam Tile -->
            <a href="{{ route('exam.setup', ['mode' => 'mock']) }}" 
               class="group p-5 rounded-2xl border border-amber-200 hover:border-amber-400 bg-gradient-to-b from-white to-amber-50/40 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-2xl block mb-2">⚡</span>
                    <h4 class="font-black text-slate-900 text-sm font-heading group-hover:text-amber-700">Timed Mock Exam</h4>
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

        <!-- Right Col: AI Coach & Referral Sharing -->
        <div class="space-y-6">
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
