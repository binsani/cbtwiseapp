<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8" 
     @if($explainingQuestionId) wire:poll.2s="checkAiExplanationStatus" @endif>
    
    <!-- Top Result Banner -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight font-heading">
            CBT Exam <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-blue-600">Results Report</span>
        </h1>
        <p class="mt-2 text-sm text-gray-600">Completed on {{ $examSession->submitted_at->format('M d, Y h:i A') }}</p>
    </div>

    <!-- Overall Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Score Card -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-28 h-28 bg-emerald-50 rounded-full z-0"></div>
            <div class="z-10">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Overall Score</span>
                <span class="text-5xl font-black text-emerald-600 font-heading">
                    {{ round($examSession->score) }}
                </span>
                <span class="text-gray-400 text-sm block mt-1">
                    {{ $examSession->exam->slug === 'utme' ? 'out of 400' : '%' }}
                </span>
            </div>
        </div>

        <!-- Correct Answers Card -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-28 h-28 bg-blue-50 rounded-full z-0"></div>
            <div class="z-10">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Accuracy</span>
                <span class="text-5xl font-black text-blue-600 font-heading">
                    {{ $examSession->total_questions > 0 ? round(($examSession->correct_count / $examSession->total_questions) * 100) : 0 }}%
                </span>
                <span class="text-gray-400 text-sm block mt-1">
                    {{ $examSession->correct_count }} / {{ $examSession->total_questions }} correct
                </span>
            </div>
        </div>

        <!-- Duration Card -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-28 h-28 bg-indigo-50 rounded-full z-0"></div>
            <div class="z-10">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Exam Duration</span>
                <span class="text-4xl font-black text-indigo-600 font-heading mt-2 block">
                    {{ gmdate('H:i:s', $examSession->duration_seconds) }}
                </span>
                <span class="text-gray-400 text-sm block mt-2">
                    {{ $examSession->mode }} mode
                </span>
            </div>
        </div>
    </div>

    <!-- Subject Wise Breakdown -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden p-8 mb-10">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 font-heading">Subject Analysis</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3 font-medium">Subject</th>
                        <th class="pb-3 font-medium text-center">Correct Answered</th>
                        <th class="pb-3 font-medium text-center">Percentage</th>
                        <th class="pb-3 font-medium text-right">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($breakdown as $subId => $data)
                        <tr class="text-sm">
                            <td class="py-4 font-bold text-gray-800">{{ $data['subject_name'] }}</td>
                            <td class="py-4 text-center font-semibold text-gray-600">{{ $data['correct'] }} / {{ $data['total'] }}</td>
                            <td class="py-4 text-center font-bold text-gray-900">{{ $data['percentage'] }}%</td>
                            <td class="py-4 text-right font-black text-emerald-600 font-heading">
                                {{ $this->getWaecGrade($data['percentage']) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Question Review Section -->
    <div class="space-y-6">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 font-heading">Question-by-Question Review</h3>

        @if (session()->has('error'))
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl text-sm font-semibold mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @foreach($reviewQuestions as $index => $q)
            <div class="bg-white rounded-3xl border-2 shadow-sm p-6 sm:p-8 transition-all duration-300
                 {{ $q['is_correct'] ? 'border-emerald-500/30' : 'border-rose-500/20' }}">
                
                <!-- Question Header -->
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 text-xs font-bold uppercase rounded-full tracking-wider
                        {{ $q['is_correct'] ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                        Question {{ $index + 1 }} — {{ $q['is_correct'] ? 'Correct' : 'Incorrect' }}
                    </span>
                    
                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <button wire:click="$dispatch('openReportModal', { questionId: {{ $q['id'] }} })"
                                class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-100 flex items-center space-x-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Report Error</span>
                        </button>

                        @if(Auth::user()->isPremium())
                            <button wire:click="getAiExplanation({{ $q['id'] }})"
                                    class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 flex items-center space-x-1.5 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                <span>AI Explanation</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Question Text -->
                <div class="prose max-w-none text-gray-800 text-lg mb-6 leading-relaxed">
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
                                {{ $q['correct_option'] === $opt ? 'border-emerald-500 bg-emerald-50/30 text-emerald-800 font-bold' : ($q['selected_option'] === $opt ? 'border-rose-500 bg-rose-50/30 text-rose-800 font-bold' : 'border-gray-100 bg-gray-50 text-gray-600') }}">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center border text-[10px] font-bold uppercase
                                    {{ $q['correct_option'] === $opt ? 'bg-emerald-500 border-emerald-500 text-white' : ($q['selected_option'] === $opt ? 'bg-rose-500 border-rose-500 text-white' : 'bg-white border-gray-200 text-gray-400') }}">
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
                        <div class="flex items-center space-x-2 text-indigo-800 font-bold text-xs uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
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

    <!-- Final dashboard redirect -->
    <div class="mt-12 text-center">
        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-blue-600 text-white font-extrabold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Return to Dashboard</span>
        </a>
    </div>

    <!-- Livewire Modal -->
    @livewire('exam.report-question')
</div>
