<div class="h-dvh flex flex-col bg-gray-50 select-none overflow-hidden"
     x-data="examRunner({
        timeRemaining: @entangle('timeRemaining'),
        currentIndex: @entangle('currentIndex'),
        selectedSubjectId: @entangle('selectedSubjectId')
     })"
     x-init="init()"
     @keydown.window="handleKey($event)">

    <!-- Top Navigation Bar -->
    <header class="bg-gradient-to-r from-emerald-700 to-emerald-950 text-white px-3 sm:px-6 py-3 flex justify-between items-center shadow-md flex-shrink-0 gap-2">
        <div class="flex items-center gap-2 sm:gap-4 min-w-0">
            <h2 class="text-sm sm:text-xl font-extrabold tracking-wider font-heading whitespace-nowrap">CBT<span class="hidden sm:inline">Wise</span></h2>
            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-white/20 rounded-full text-[9px] sm:text-xs font-bold uppercase tracking-widest whitespace-nowrap">{{ $mode }}</span>
            @if($topicName)
                <span class="hidden lg:inline-flex items-center px-2.5 py-1 bg-amber-400/20 text-amber-200 border border-amber-400/30 rounded-full text-[10px] font-extrabold tracking-wide truncate max-w-xs" title="{{ $topicName }}">
                    🎯 {{ $topicName }}
                </span>
            @endif
        </div>

        <!-- Timer — full hh:mm:ss on md+, compact mm:ss on mobile -->
        <div class="flex items-center gap-1.5 sm:gap-3 bg-white/10 px-3 sm:px-5 py-1.5 sm:py-2 rounded-2xl border border-white/10 shadow-inner flex-shrink-0">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-300 animate-pulse flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-mono text-base sm:text-xl font-bold tracking-widest text-emerald-100 tabular-nums" x-text="formatTime()">00:00</span>
        </div>

        <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
            <!-- Calculator toggle — icon only on mobile -->
            <button type="button"
                    @click="showCalc = !showCalc"
                    class="p-2 sm:px-4 sm:py-2 bg-emerald-800/80 hover:bg-emerald-600 active:bg-emerald-900 border border-emerald-500/30 rounded-xl text-xs font-bold text-white flex items-center gap-1.5 transition-all shadow-sm"
                    title="Toggle Calculator">
                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span class="hidden sm:inline">Calculator</span>
            </button>

            <div class="text-right hidden md:block">
                <p class="text-xs text-emerald-200">Candidate</p>
                <p class="text-sm font-bold truncate max-w-[120px]">{{ Auth::user()->name }}</p>
            </div>
            <button @click="confirmSubmit()" class="px-3 sm:px-5 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 rounded-xl font-bold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all duration-300 whitespace-nowrap">
                End Exam
            </button>
        </div>
    </header>

    <!-- Subject Tabs -->
    <div class="bg-white border-b border-gray-200 px-3 sm:px-6 py-3 flex space-x-2 overflow-x-auto flex-shrink-0 scrollbar-none touch-scroll">
        @foreach($subjectList as $subj)
            <button wire:click="selectSubject({{ $subj['id'] }})"
                    class="px-4 sm:px-5 py-2.5 rounded-xl font-bold text-sm tracking-wide transition-all duration-300 flex items-center space-x-2 flex-shrink-0
                    {{ $selectedSubjectId == $subj['id'] ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-600 bg-gray-100 hover:bg-gray-200' }}">
                <span>{{ $subj['name'] }}</span>
            </button>
        @endforeach
    </div>

    <!-- Mobile: Slide-up Question Palette Drawer -->
    <div x-show="showPalette"
         x-cloak
         @click.self="showPalette = false"
         class="fixed inset-0 z-50 bg-black/50 lg:hidden flex items-end">
        <div class="w-full bg-white rounded-t-3xl max-h-[70vh] flex flex-col overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 flex-shrink-0">
                <h3 class="text-base font-black text-slate-900">Question Navigator</h3>
                <button @click="showPalette = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <!-- Legend -->
            <div class="px-5 py-2 flex items-center gap-4 text-xs text-slate-500 flex-shrink-0 border-b border-slate-50">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-emerald-500 rounded-sm"></span>Answered</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-amber-500 rounded-sm"></span>Flagged</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-slate-200 rounded-sm"></span>Unanswered</span>
            </div>
            <!-- Grid -->
            <div class="p-4 overflow-y-auto grid grid-cols-6 sm:grid-cols-8 gap-2">
                @foreach($questionsList as $idx => $q)
                    <button @click="currentIndex = {{ $idx }}; showPalette = false"
                            class="h-10 rounded-xl flex items-center justify-center font-bold text-sm transition-all duration-200 border-2
                            {{ $currentIndex == $idx ? 'border-emerald-600 scale-105 shadow-sm' : 'border-transparent' }}
                            {{ $flagged[$q->id] ?? false ? 'bg-amber-500 text-white' : (($answers[$q->id] ?? null) ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') }}">
                        {{ $idx + 1 }}
                    </button>
                @endforeach
            </div>
            <!-- Submit -->
            <div class="px-4 pb-6 pt-2 flex-shrink-0 border-t border-slate-100">
                <button @click="confirmSubmit()" class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-extrabold rounded-2xl shadow-lg transition-all">
                    Submit Exam Sheet
                </button>
            </div>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Left Column: Question & Options -->
        <div class="flex-1 flex flex-col p-3 sm:p-6 overflow-y-auto min-w-0 pb-20 lg:pb-6">
            @if($activeQuestion)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 sm:p-8 flex-1 flex flex-col justify-between">
                    <div>
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-4 sm:mb-6">
                            <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest whitespace-nowrap">
                                    Q {{ $currentIndex + 1 }} / {{ $questionsList->count() }}
                                </span>
                                @if($topicName)
                                    <span class="text-slate-300 hidden sm:inline">&bull;</span>
                                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-md truncate max-w-[120px] sm:max-w-sm hidden sm:inline">
                                        {{ $topicName }}
                                    </span>
                                @elseif($activeQuestion->topic)
                                    <span class="text-slate-300 hidden sm:inline">&bull;</span>
                                    <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-md truncate max-w-[120px] sm:max-w-sm hidden sm:inline">
                                        {{ $activeQuestion->topic->name }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Toggle Scientific Calculator -->
                            @php
                                $currentSubj = collect($subjectList)->firstWhere('id', $selectedSubjectId);
                                $currentSubjName = strtolower($currentSubj['name'] ?? '');
                            @endphp
                            @if(in_array($currentSubjName, ['mathematics', 'physics', 'chemistry', 'economics', 'financial accounting', 'further mathematics']))
                                <button @click="showCalc = !showCalc" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-xl border border-blue-100 flex items-center space-x-1.5 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span>Calculator</span>
                                </button>
                            @endif
                        </div>

                        <!-- Question Body -->
                        <div class="prose max-w-none text-gray-800 text-base sm:text-lg mb-6 sm:mb-8 leading-relaxed overflow-x-auto break-words">
                            {!! $activeQuestion->question_text !!}
                            
                            @if($activeQuestion->question_image)
                                <div class="mt-4">
                                    <img src="{{ asset('storage/' . $activeQuestion->question_image) }}" alt="Question Illustration" class="max-h-64 rounded-xl border border-gray-100 shadow-sm" loading="lazy">
                                </div>
                            @endif
                        </div>

                        <!-- Options List -->
                        <div class="space-y-4">
                            @foreach($activeQuestion->getOptions() as $key => $text)
                                <div wire:click="selectOption({{ $activeQuestion->id }}, '{{ $key }}')"
                                    class="group border-2 rounded-2xl p-4 sm:p-5 cursor-pointer flex items-center space-x-3 sm:space-x-4 transition-all duration-300 hover:shadow-sm
                                     {{ ($answers[$activeQuestion->id] ?? null) === $key ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-100 bg-white hover:border-emerald-100' }}">
                                    
                                    <!-- Option Key Badge -->
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm uppercase transition-all duration-300 border-2
                                        {{ ($answers[$activeQuestion->id] ?? null) === $key ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-gray-50 border-gray-200 text-gray-500 group-hover:bg-emerald-100 group-hover:text-emerald-700' }}">
                                        {{ $key }}
                                    </span>
                                    
                                    <!-- Option Content -->
                                    <span class="min-w-0 break-words text-gray-700 font-medium leading-relaxed group-hover:text-emerald-950">{!! $text !!}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom Nav Actions inside question card (visible on sm+) -->
                    <div class="mt-6 sm:mt-10 pt-4 sm:pt-6 border-t border-gray-100 hidden sm:flex justify-between items-center">
                        <button type="button" @click="prevQuestion()"
                                class="px-5 py-3 border border-gray-300 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 transition-colors flex items-center space-x-2 disabled:opacity-40"
                                :disabled="currentIndex === 0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            <span>Previous</span>
                        </button>

                            <div class="flex items-center space-x-2">
                                <button type="button" wire:click="toggleFlag({{ $activeQuestion->id }})"
                                        class="px-4 py-3 border rounded-2xl font-bold flex items-center space-x-2 transition-all duration-300
                                        {{ $flagged[$activeQuestion->id] ?? false ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="w-4 h-4 {{ $flagged[$activeQuestion->id] ?? false ? 'fill-amber-500 text-amber-500' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                    <span>Flag</span>
                                </button>

                                <button type="button" wire:click="toggleBookmark({{ $activeQuestion->id }})"
                                        class="px-4 py-3 border rounded-2xl font-bold flex items-center space-x-2 transition-all duration-300
                                        {{ $bookmarked[$activeQuestion->id] ?? false ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                                        title="Save question to bookmarks">
                                    <span>🔖</span>
                                    <span>{{ $bookmarked[$activeQuestion->id] ?? false ? 'Saved' : 'Bookmark' }}</span>
                                </button>

                                <button type="button"
                                        wire:click="$dispatch('openReportModal', { questionId: {{ $activeQuestion->id }} })"
                                        class="px-3 py-3 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-2xl font-bold flex items-center space-x-1.5 transition-all text-xs"
                                        title="Report issue with this question">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Report</span>
                                </button>
                            </div>

                            <button type="button" @click="nextQuestion({{ $questionsList->count() }})"
                                    class="px-5 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-colors flex items-center space-x-2 disabled:opacity-40"
                                    :disabled="currentIndex === {{ $questionsList->count() - 1 }}">
                                <span>Next</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
            @else
                <div class="flex-1 bg-white rounded-3xl border border-gray-100 flex items-center justify-center p-8 text-center">
                    <p class="text-gray-500 font-medium">No questions loaded for this subject.</p>
                </div>
            @endif
        </div>

        <!-- Mobile: Sticky Bottom Action Bar (hidden on lg+) -->
        @if($activeQuestion)
        <div class="fixed bottom-0 left-0 right-0 z-30 lg:hidden bg-white border-t border-slate-200 px-2 py-2 shadow-2xl pb-[max(0.5rem,env(safe-area-inset-bottom))]">
            <div class="flex items-center gap-1">
                <!-- Prev -->
                <button type="button" @click="prevQuestion()"
                        class="flex-1 flex flex-col items-center justify-center py-2 rounded-xl font-bold text-slate-600 hover:bg-slate-100 disabled:opacity-30 transition-colors"
                        :disabled="currentIndex === 0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    <span class="text-[9px] font-black">Prev</span>
                </button>

                <!-- Flag -->
                <button type="button" wire:click="toggleFlag({{ $activeQuestion->id }})"
                        class="flex-1 flex flex-col items-center justify-center py-2 rounded-xl font-bold transition-colors {{ $flagged[$activeQuestion->id] ?? false ? 'text-amber-600 bg-amber-50' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ $flagged[$activeQuestion->id] ?? false ? 'fill-amber-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    <span class="text-[9px] font-black">Flag</span>
                </button>

                <!-- Q Counter — opens drawer -->
                <button type="button" @click="showPalette = true"
                        class="flex-[1.5] flex flex-col items-center justify-center py-2 rounded-xl bg-emerald-600 text-white font-bold transition-colors active:bg-emerald-700">
                    <span class="text-sm font-black leading-tight" x-text="(currentIndex + 1) + '/' + {{ $questionsList->count() }}"></span>
                    <span class="text-[9px] font-black">Palette ↑</span>
                </button>

                <!-- Bookmark -->
                <button type="button" wire:click="toggleBookmark({{ $activeQuestion->id }})"
                        class="flex-1 flex flex-col items-center justify-center py-2 rounded-xl font-bold transition-colors {{ $bookmarked[$activeQuestion->id] ?? false ? 'text-indigo-600 bg-indigo-50' : 'text-slate-500 hover:bg-slate-100' }}">
                    <span class="text-lg leading-none">🔖</span>
                    <span class="text-[9px] font-black">Save</span>
                </button>

                <!-- Next -->
                <button type="button" @click="nextQuestion({{ $questionsList->count() }})"
                        class="flex-1 flex flex-col items-center justify-center py-2 rounded-xl font-bold text-slate-600 hover:bg-slate-100 disabled:opacity-30 transition-colors"
                        :disabled="currentIndex === {{ $questionsList->count() - 1 }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-[9px] font-black">Next</span>
                </button>
            </div>
        </div>
        @endif

        <!-- Right Column: Navigation Palette -->
        <aside class="w-80 bg-white border-l border-gray-200 p-6 overflow-y-auto hidden lg:flex flex-col justify-between flex-shrink-0">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 font-heading">Question Navigator</h3>
                
                <!-- Indicators description -->
                <div class="grid grid-cols-2 gap-2 mb-6 text-xs text-gray-500">
                    <div class="flex items-center space-x-2"><span class="w-3.5 h-3.5 bg-emerald-500 rounded-md"></span><span>Answered</span></div>
                    <div class="flex items-center space-x-2"><span class="w-3.5 h-3.5 bg-amber-500 rounded-md"></span><span>Flagged</span></div>
                    <div class="flex items-center space-x-2"><span class="w-3.5 h-3.5 border-2 border-emerald-600 rounded-md"></span><span>Active</span></div>
                    <div class="flex items-center space-x-2"><span class="w-3.5 h-3.5 bg-gray-100 rounded-md"></span><span>Unanswered</span></div>
                </div>

                <!-- Palette Grid -->
                <div class="grid grid-cols-4 gap-3">
                    @foreach($questionsList as $idx => $q)
                        <button @click="currentIndex = {{ $idx }}"
                                class="h-11 rounded-xl flex items-center justify-center font-bold text-sm tracking-wide transition-all duration-300 border-2
                                {{ $currentIndex == $idx ? 'border-emerald-600 scale-105 shadow-sm' : 'border-transparent' }}
                                {{ $flagged[$q->id] ?? false ? 'bg-amber-500 text-white' : (($answers[$q->id] ?? null) ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200') }}">
                            {{ $idx + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Submit card -->
            <div class="border-t border-gray-100 pt-6 mt-6">
                <button @click="confirmSubmit()" class="w-full py-4 bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-extrabold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                    Submit Exam Sheet
                </button>
            </div>
        </aside>
    </div>

    <!-- Alpine.js Scientific Calculator Modal / Dialog -->
    <div x-show="showCalc" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm sm:bg-transparent sm:backdrop-blur-none sm:p-0 sm:fixed sm:top-20 sm:left-24 sm:w-auto" 
         @keydown.escape.window="showCalc = false">
        
        <div class="bg-gray-800 text-white rounded-2xl shadow-2xl overflow-hidden border border-gray-700 w-80 max-w-full sm:w-72"
             x-data="calculator()"
             @click.away="showCalc = false">
            
            <!-- Header -->
            <div class="cursor-move bg-gray-900 px-4 py-3 sm:py-2 flex justify-between items-center text-xs font-bold tracking-widest text-gray-300 select-none border-b border-gray-700">
                <span class="flex items-center gap-1.5">
                    <span class="text-emerald-400">🔢</span> JAMB CBT CALCULATOR
                </span>
                <button @click="showCalc = false" class="p-1 rounded-lg hover:bg-gray-700 text-rose-400 hover:text-rose-300 font-bold text-lg leading-none">&times;</button>
            </div>

            <!-- Display -->
            <div class="p-4 bg-gray-950 text-right">
                <div class="text-[11px] text-gray-500 font-mono min-h-[16px]" x-text="expression"></div>
                <div class="text-2xl font-bold font-mono tracking-wider truncate text-emerald-400" x-text="display">0</div>
            </div>

            <!-- Keys Grid -->
            <div class="grid grid-cols-5 gap-1.5 p-3 bg-gray-900 text-xs font-semibold">
                <!-- Row 1 -->
                <button @click="clear()" class="py-3 sm:py-2.5 bg-gray-700 hover:bg-gray-600 rounded-lg text-rose-300 font-bold">C</button>
                <button @click="op('(')" class="py-3 sm:py-2.5 bg-gray-700 hover:bg-gray-600 rounded-lg">(</button>
                <button @click="op(')')" class="py-3 sm:py-2.5 bg-gray-700 hover:bg-gray-600 rounded-lg">)</button>
                <button @click="backspace()" class="py-3 sm:py-2.5 bg-gray-700 hover:bg-gray-600 rounded-lg">DEL</button>
                <button @click="op('/')" class="py-3 sm:py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-lg">/</button>

                <!-- Row 2 -->
                <button @click="func('sin')" class="py-3 sm:py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-[10px]">sin</button>
                <button @click="num('7')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">7</button>
                <button @click="num('8')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">8</button>
                <button @click="num('9')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">9</button>
                <button @click="op('*')" class="py-3 sm:py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-lg">&times;</button>

                <!-- Row 3 -->
                <button @click="func('cos')" class="py-3 sm:py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-[10px]">cos</button>
                <button @click="num('4')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">4</button>
                <button @click="num('5')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">5</button>
                <button @click="num('6')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">6</button>
                <button @click="op('-')" class="py-3 sm:py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-lg">-</button>

                <!-- Row 4 -->
                <button @click="func('tan')" class="py-3 sm:py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-[10px]">tan</button>
                <button @click="num('1')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">1</button>
                <button @click="num('2')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">2</button>
                <button @click="num('3')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">3</button>
                <button @click="op('+')" class="py-3 sm:py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-lg">+</button>

                <!-- Row 5 -->
                <button @click="func('sqrt')" class="py-3 sm:py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-[10px]">&radic;</button>
                <button @click="num('0')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg col-span-2">0</button>
                <button @click="num('.')" class="py-3 sm:py-2.5 bg-gray-600 hover:bg-gray-500 rounded-lg">.</button>
                <button @click="calculate()" class="py-3 sm:py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg">=</button>
            </div>
        </div>
    </div>

    <!-- Alpine / JS Helpers -->
    @script
        document.addEventListener('alpine:init', () => {
            Alpine.data('examRunner', (config) => ({
                timeRemaining: config.timeRemaining,
                currentIndex: config.currentIndex,
                selectedSubjectId: config.selectedSubjectId,
                showCalc: false,
                showPalette: false,

                timerInterval: null,
                isSubmitting: false,

                init() {
                    // Update timer on intervals
                    this.timerInterval = setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                            // Push back to Livewire every 30s
                            if (this.timeRemaining % 30 === 0) {
                                @this.call('syncTimer', this.timeRemaining);
                            }
                        } else {
                            if (this.timerInterval) {
                                clearInterval(this.timerInterval);
                                this.timerInterval = null;
                            }
                            if (!this.isSubmitting) {
                                this.isSubmitting = true;
                                @this.call('autoSubmit');
                            }
                        }
                    }, 1000);
                },

                formatTime() {
                    let h = Math.floor(this.timeRemaining / 3600);
                    let m = Math.floor((this.timeRemaining % 3600) / 60);
                    let s = this.timeRemaining % 60;
                    return [
                        h.toString().padStart(2, '0'),
                        m.toString().padStart(2, '0'),
                        s.toString().padStart(2, '0')
                    ].join(':');
                },

                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                        @this.call('navigate', this.currentIndex);
                    }
                },

                nextQuestion(maxCount) {
                    if (this.currentIndex < maxCount - 1) {
                        this.currentIndex++;
                        @this.call('navigate', this.currentIndex);
                    }
                },

                handleKey(e) {
                    // Do not trigger exam navigation/answering shortcuts if student is typing or calculator is open
                    if (this.showCalc || ['input', 'textarea', 'select'].includes(document.activeElement?.tagName?.toLowerCase())) {
                        return;
                    }
                    let key = e.key.toLowerCase();
                    if (['a', 'b', 'c', 'd', 'e'].includes(key)) {
                        let activeId = @this.get('activeQuestion.id');
                        if (activeId) {
                            @this.call('selectOption', activeId, key);
                        }
                    } else if (key === 'arrowright' || key === 'n') {
                        this.nextQuestion(@this.get('questionsList').length);
                    } else if (key === 'arrowleft' || key === 'p') {
                        this.prevQuestion();
                    } else if (key === 'f') {
                        let activeId = @this.get('activeQuestion.id');
                        if (activeId) {
                            @this.call('toggleFlag', activeId);
                        }
                    }
                },

                confirmSubmit() {
                    if (this.isSubmitting) return;
                    if (confirm('Are you sure you want to end your exam session and submit?')) {
                        this.isSubmitting = true;
                        if (this.timerInterval) {
                            clearInterval(this.timerInterval);
                            this.timerInterval = null;
                        }
                        @this.call('submit');
                    }
                }
            }));

            Alpine.data('calculator', () => ({
                display: '0',
                expression: '',
                clear() {
                    this.display = '0';
                    this.expression = '';
                },
                num(val) {
                    if (this.display === '0') {
                        this.display = val;
                    } else {
                        this.display += val;
                    }
                },
                op(val) {
                    this.display += ' ' + val + ' ';
                },
                backspace() {
                    this.display = this.display.trim();
                    if (this.display.length <= 1) {
                        this.display = '0';
                    } else {
                        this.display = this.display.slice(0, -1);
                    }
                },
                func(type) {
                    if (type === 'sin') {
                        this.display = Math.sin(parseFloat(this.display) * Math.PI / 180).toFixed(6);
                    } else if (type === 'cos') {
                        this.display = Math.cos(parseFloat(this.display) * Math.PI / 180).toFixed(6);
                    } else if (type === 'tan') {
                        this.display = Math.tan(parseFloat(this.display) * Math.PI / 180).toFixed(6);
                    } else if (type === 'sqrt') {
                        this.display = Math.sqrt(parseFloat(this.display)).toFixed(6);
                    }
                },
                calculate() {
                    try {
                        let result = eval(this.display.replace(/\s+/g, ''));
                        this.expression = this.display;
                        this.display = result.toString();
                    } catch (e) {
                        this.display = 'Error';
                    }
                }
            }));
        });
    @endscript

    <!-- Livewire Question Reporting Modal -->
    @livewire('exam.report-question')
</div>
