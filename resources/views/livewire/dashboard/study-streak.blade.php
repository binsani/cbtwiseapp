<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 font-sans space-y-8">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-heading">Study Streak Tracker</h1>
            <p class="text-slate-500 text-sm mt-1">Consistency is key to acing your exams. Keep the flame alive!</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50 transition-colors">
            &larr; Back to Dashboard
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl text-xs font-bold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        
        <!-- Flame and Token Info -->
        <div class="md:col-span-1 space-y-6">
            <!-- Streak Flame Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm text-center space-y-4">
                <div class="text-6xl animate-bounce">🔥</div>
                <div>
                    <span class="text-5xl font-black text-slate-950 font-heading block">{{ $streakDays }}</span>
                    <span class="text-xs font-bold text-slate-450 uppercase tracking-widest mt-1 block">Day Study Streak</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Practice at least one CBT question daily to keep your streak active.
                </p>
            </div>

            <!-- Freeze Token Card -->
            <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold">Streak Freezes</span>
                    <span class="px-3 py-1 bg-white/10 rounded-xl text-xs font-black">
                        {{ $freezeTokens }} Available
                    </span>
                </div>
                <p class="text-xs text-slate-350 leading-relaxed">
                    A streak freeze automatically keeps your streak alive when you miss a day of practice.
                </p>
                <button 
                    wire:click="purchaseFreezeToken" 
                    class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-2xl text-xs shadow-md transition-colors"
                >
                    Redeem Freeze (Cost: 5 CBTs completed)
                </button>
            </div>
        </div>

        <!-- Heatmap Calendar & Milestones -->
        <div class="md:col-span-2 space-y-8">
            
            <!-- Heatmap Calendar Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-50 pb-2">Active Study Days</h3>
                
                <!-- Simplified Heatmap Calendar (Past 30 Days grid) -->
                <div class="grid grid-cols-7 gap-2 text-center text-xs">
                    @php
                        $days = [];
                        for ($i = 29; $i >= 0; $i--) {
                            $days[] = now()->subDays($i);
                        }
                        $weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    @endphp
                    
                    @foreach($days as $day)
                        @php
                            $formatted = $day->format('Y-m-d');
                            $isActive = in_array($formatted, $activeDates);
                            $isToday = $day->isToday();
                        @endphp
                        
                        <div class="flex flex-col items-center p-2 rounded-xl transition-all duration-300
                            {{ $isActive ? 'bg-emerald-50 text-emerald-800 border border-emerald-100 font-black' : 'bg-slate-50 text-slate-400 border border-slate-50' }}
                            {{ $isToday ? 'ring-2 ring-emerald-500' : '' }}">
                            <span class="text-[9px] uppercase font-bold text-slate-400 block mb-1">{{ $day->format('D') }}</span>
                            <span class="text-xs block">{{ $day->format('d') }}</span>
                            @if($isActive)
                                <span class="text-[8px] mt-1 block">✅</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Milestones Badges Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-50 pb-2">Streak Milestones</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($milestones as $milestone)
                        <div class="flex items-center gap-4 p-4 rounded-2xl border transition-all
                            {{ $milestone['achieved'] ? 'bg-emerald-50/30 border-emerald-100 text-slate-800' : 'bg-slate-50/50 border-slate-100 text-slate-400' }}">
                            <div class="text-3xl">
                                {{ $milestone['achieved'] ? '🏆' : '🔒' }}
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">{{ $milestone['label'] }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Reach a {{ $milestone['days'] }}-day streak</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
