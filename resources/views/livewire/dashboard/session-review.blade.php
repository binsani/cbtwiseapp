<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8" 
     @if($explainingQuestionId) wire:poll.2s="checkAiExplanationStatus" @endif>
    
    <!-- Top Result Banner -->
    <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">
                CBT Session <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Review</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500">Completed on {{ $examSession->submitted_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('dashboard.history') }}" class="px-4 py-2 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50 transition-colors">
            &larr; Back to History
        </a>
    </div>

    <!-- Overall Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Score Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-28 h-28 bg-emerald-50 rounded-full z-0"></div>
            <div class="z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Overall Score</span>
                <span class="text-5xl font-black text-emerald-600 font-heading">
                    {{ round($examSession->score) }}
                </span>
                <span class="text-slate-400 text-sm block mt-1">
                    {{ $examSession->exam->slug === 'utme' ? 'out of 400' : '%' }}
                </span>
            </div>
        </div>

        <!-- Correct Answers Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-28 h-28 bg-teal-50 rounded-full z-0"></div>
            <div class="z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Accuracy</span>
                <span class="text-5xl font-black text-teal-600 font-heading">
                    {{ $examSession->total_questions > 0 ? round(($examSession->correct_count / $examSession->total_questions) * 100) : 0 }}%
                </span>
                <span class="text-slate-400 text-sm block mt-1">
                    {{ $examSession->correct_count }} / {{ $examSession->total_questions }} correct
                </span>
            </div>
        </div>

        <!-- Duration Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-28 h-28 bg-slate-50 rounded-full z-0"></div>
            <div class="z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Time Spent</span>
                <span class="text-4xl font-black text-slate-800 font-heading mt-2 block">
                    {{ gmdate('H:i:s', $examSession->duration_seconds) }}
                </span>
                <span class="text-slate-400 text-sm block mt-2">
                    {{ ucfirst($examSession->mode) }} mode
                </span>
            </div>
        </div>
    </div>

    <!-- Subject Wise Breakdown -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-8 mb-10">
        <h3 class="text-xl font-bold text-slate-900 mb-6 font-heading">Subject Analysis</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3 font-semibold">Subject</th>
                        <th class="pb-3 font-semibold text-center">Correct Answered</th>
                        <th class="pb-3 font-semibold text-center">Percentage</th>
                        <th class="pb-3 font-semibold text-right">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($breakdown as $subId => $data)
                        <tr class="text-sm">
                            <td class="py-4 font-bold text-slate-800">{{ $data['subject_name'] }}</td>
                            <td class="py-4 text-center font-semibold text-slate-600">{{ $data['correct'] }} / {{ $data['total'] }}</td>
                            <td class="py-4 text-center font-bold text-slate-900">{{ $data['percentage'] }}%</td>
                            <td class="py-4 text-right font-black text-emerald-600 font-heading">
                                {{ $data['percentage'] >= 75 ? 'Excellent' : ($data['percentage'] >= 50 ? 'Average' : 'Needs Review') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Question Review Section -->
    <div class="space-y-6">
        <h3 class="text-xl font-bold text-slate-900 mb-6 font-heading">Question Review</h3>

        @if (session()->has('error'))
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl text-xs font-semibold mb-6">
                {{ session('error') }}
            </div>
        @endif

        @foreach($reviewQuestions as $index => $q)
            <div class="bg-white rounded-3xl border shadow-sm p-6 sm:p-8 transition-all duration-300
                 {{ $q['is_correct'] ? 'border-emerald-500/20' : 'border-rose-500/20' }}">
                
                <!-- Question Header -->
                <div class="flex justify-between items-start gap-4 mb-4">
                    <span class="px-3 py-1 text-xs font-bold uppercase rounded-xl tracking-wider
                        {{ $q['is_correct'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                        Question {{ $index + 1 }} — {{ $q['is_correct'] ? 'Correct' : 'Incorrect' }}
                    </span>
                    
                    @if(Auth::user()->isPremium())
                        <button wire:click="getAiExplanation({{ $q['id'] }})"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 flex items-center space-x-1 transition-colors">
                            🤖 <span>AI Explanation</span>
                        </button>
                    @endif
                </div>

                <!-- Question Text -->
                <div class="text-slate-800 text-base mb-6 leading-relaxed">
                    {!! $q['question_text'] !!}
                    @if($q['question_image'])
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $q['question_image']) }}" alt="Question Image" class="max-h-48 rounded-xl">
                        </div>
                    @endif
                </div>

                <!-- Options grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                    @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                        @if($q['option_' . $opt])
                            <div class="p-3 border rounded-xl flex items-center space-x-3 text-sm font-semibold
                                {{ $q['correct_option'] === $opt ? 'border-emerald-500 bg-emerald-50/30 text-emerald-800 font-bold' : ($q['selected_option'] === $opt ? 'border-rose-500 bg-rose-50/30 text-rose-800 font-bold' : 'border-slate-100 bg-slate-50 text-slate-600') }}">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center border text-[10px] font-bold uppercase
                                    {{ $q['correct_option'] === $opt ? 'bg-emerald-500 border-emerald-500 text-white' : ($q['selected_option'] === $opt ? 'bg-rose-500 border-rose-500 text-white' : 'bg-white border-slate-200 text-slate-400') }}">
                                    {{ $opt }}
                                </span>
                                <span>{!! $q['option_' . $opt] !!}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Explanation / AI Explanation Block -->
                @if($q['explanation'] || ($explainingQuestionId == $q['id'] && $aiExplanation))
                    <div class="mt-4 bg-slate-50 rounded-2xl border border-slate-100 p-5">
                        <div class="flex items-center space-x-2 text-indigo-850 font-bold text-xs uppercase tracking-wider mb-2">
                            <span>Explanation</span>
                        </div>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            @if($explainingQuestionId == $q['id'])
                                {!! nl2br(e($aiExplanation)) !!}
                            @else
                                {!! nl2br(e($q['explanation'])) !!}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
