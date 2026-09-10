<div>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-1">
                    <span>Curriculum Master</span>
                    <span>&bull;</span>
                    <span>Syllabus & Topic Practice</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading">
                    Practice By Topic
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Drill down into specific topics across UTME, WAEC, and NECO syllabuses. Strengthen weak areas with targeted questions.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('exam.setup') }}" 
                   class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-black shadow-sm transition-all flex items-center gap-1.5">
                    <span>Full CBT Exam Setup</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Exam Switcher Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            @foreach($exams as $exam)
                <button type="button" 
                        wire:click="selectExam({{ $exam->id }})"
                        class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap
                        {{ $selectedExamId == $exam->id ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    {{ $exam->name }}
                </button>
            @endforeach
        </div>

        <!-- Subject Tabs Grid -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 font-heading">
                    1. Choose Subject
                </h3>
                <span class="text-xs text-slate-400">{{ count($subjects) }} Subjects in syllabus</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2.5">
                @foreach($subjects as $subj)
                    <button type="button" 
                            wire:click="selectSubject({{ $subj->id }})"
                            class="p-3 rounded-2xl text-left font-bold text-xs transition-all border flex items-center gap-2
                            {{ $selectedSubjectId == $subj->id ? 'bg-emerald-50 text-emerald-800 border-emerald-500 shadow-sm ring-2 ring-emerald-500/20' : 'bg-slate-50/60 text-slate-700 border-slate-200 hover:border-emerald-300 hover:bg-white' }}">
                        <span class="w-7 h-7 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
                            {{ mb_substr($subj->name, 0, 1) }}
                        </span>
                        <span class="truncate">{{ $subj->name }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Topics Section -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-black text-slate-900 font-heading flex items-center gap-2">
                        <span>📖 {{ $activeSubject?->name ?? 'Subject' }} Syllabus Topics</span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                            {{ count($topics) }} topics
                        </span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Select a topic below to start a dedicated practice session immediately.</p>
                </div>

                <!-- Topic Search Box -->
                <div class="w-full sm:w-72">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Filter topics..." 
                           class="w-full px-4 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 placeholder:text-slate-400 transition-all">
                </div>
            </div>

            @if(count($topics) === 0)
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm space-y-3">
                    <span class="text-3xl">🔍</span>
                    <h4 class="text-base font-bold text-slate-900">No topics found</h4>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        No curriculum topics match your current filter. Clear your search or try another subject.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($topics as $topic)
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-emerald-500 transition-all duration-300 shadow-sm hover:shadow-md flex flex-col justify-between group">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md">
                                        Topic {{ $topic->sort_order }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-mono font-bold">
                                        {{ $activeExam?->slug ? strtoupper($activeExam->slug) : 'JAMB' }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                                    {{ $topic->name }}
                                </h4>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">Standard Syllabus</span>
                                <a href="{{ route('exam.setup', ['exam' => $activeExam?->slug ?? 'utme', 'subject' => $activeSubject?->id, 'topic' => $topic->id]) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition-all">
                                    <span>Practice Topic</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
