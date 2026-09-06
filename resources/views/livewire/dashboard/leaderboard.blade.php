<div class="max-w-4xl mx-auto py-4 px-4 sm:px-6 lg:px-8 font-sans space-y-8">
    
    <!-- Navigation Tabs -->
    <x-dashboard-nav />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-heading">Scholar Leaderboard</h1>
            <p class="text-slate-500 text-sm mt-1">See how you rank against other scholars in Nigeria.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50 transition-colors self-start sm:self-center">
            &larr; Back to Dashboard
        </a>
    </div>

    <!-- Alert Message -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-bold shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Rank Table -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-55 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                        <th class="p-4 sm:p-6">Rank</th>
                        <th class="p-4 sm:p-6">Scholar</th>
                        <th class="p-4 sm:p-6 text-right">Streak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($leaders as $index => $leader)
                        <tr class="hover:bg-slate-50/50 transition-colors {{ Auth::id() === $leader->id ? 'bg-emerald-50/30' : '' }}">
                            <td class="p-4 sm:p-6 font-black text-slate-800">
                                @if($index === 0)
                                    🥇 <span class="text-amber-500 text-lg">1</span>
                                @elseif($index === 1)
                                    🥈 <span class="text-slate-400 text-lg">2</span>
                                @elseif($index === 2)
                                    🥉 <span class="text-amber-700 text-lg">3</span>
                                @else
                                    #{{ $index + 1 }}
                                @endif
                            </td>
                            <td class="p-4 sm:p-6 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs">
                                    {{ substr($leader->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block">
                                        {{ $leader->name }}
                                        @if(Auth::id() === $leader->id)
                                            <span class="ml-1 text-[10px] bg-emerald-600 text-white px-1.5 py-0.5 rounded-md font-semibold uppercase">You</span>
                                        @endif
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $leader->school ?? 'General Scholar' }}</span>
                                </div>
                            </td>
                            <td class="p-4 sm:p-6 text-right font-black text-slate-800 font-heading">
                                🔥 {{ $leader->study_streak_days }} Days
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-12 text-center text-slate-400">No active scholars on the board yet. Keep practicing to take the top spot!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Privacy Settings -->
        <div class="lg:col-span-1 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-50 pb-2">Leaderboard Privacy</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                You can toggle your visibility settings. Opting out will hide your name, school, and streak progress from other students.
            </p>
            
            <button 
                wire:click="toggleOptIn" 
                class="w-full py-3 text-center text-xs font-extrabold rounded-2xl transition-all shadow-sm border
                    {{ $isOptedIn ? 'bg-slate-900 hover:bg-slate-800 text-white border-transparent' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200' }}"
            >
                {{ $isOptedIn ? 'Opt Out of Leaderboard' : 'Opt In to Leaderboard' }}
            </button>
        </div>
    </div>
</div>
