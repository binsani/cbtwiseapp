<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Progress Header -->
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight font-heading">
            Configure Your <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-blue-600">Practice Exam</span>
        </h1>
        <p class="mt-2 text-lg text-gray-600">Customize your CBT experience to fit your study goals.</p>

        <!-- Stepper -->
        <div class="mt-8 relative max-w-xl mx-auto">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full bg-gray-200 h-1 rounded"></div>
            </div>
            <div class="relative flex justify-between">
                @foreach([1 => 'Exam', 2 => 'Mode', 3 => 'Subjects', 4 => 'Finalize'] as $step => $label)
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full transition-all duration-300 font-semibold text-sm z-10 
                            {{ $currentStep == $step ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white ring-4 ring-emerald-100 scale-110 shadow-lg' : ($currentStep > $step ? 'bg-emerald-600 text-white' : 'bg-white border-2 border-gray-200 text-gray-400') }}">
                            @if($currentStep > $step)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $step }}
                            @endif
                        </div>
                        <span class="mt-2 text-xs font-semibold {{ $currentStep == $step ? 'text-emerald-600' : 'text-gray-500' }}">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Wizard Card -->
    <div class="bg-white/80 backdrop-blur-md border border-gray-100 rounded-3xl shadow-xl overflow-hidden p-8 sm:p-10 transition-all duration-300">
        <!-- Error & Alert Messages -->
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl flex items-start space-x-3 shadow-sm animate-shake">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <!-- STEP 1: SELECT EXAM -->
        @if($currentStep === 1)
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-gray-900 font-heading">Choose an Examination Board</h3>
                <p class="text-gray-500 text-sm">Select the specific exam curriculum you wish to practice.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($exams as $exam)
                        @php
                            if (is_string($exam)) {
                                $exam = \App\Models\Exam::where('slug', $exam)->orWhere('id', $exam)->first();
                            }
                        @endphp
                        @if($exam)
                            <div wire:click="$set('selectedExamId', {{ $exam->id }})"
                                 class="group relative border-2 rounded-2xl p-6 cursor-pointer hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-48
                                 {{ $selectedExamId == $exam->id ? 'border-emerald-500 bg-emerald-50/40 ring-4 ring-emerald-50' : 'border-gray-200 bg-white hover:border-emerald-200' }}">
                                
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-widest px-3 py-1 bg-gray-100 rounded-full text-gray-500 group-hover:bg-emerald-100 group-hover:text-emerald-700 transition-colors duration-300">
                                        {{ $exam->slug }}
                                    </span>
                                    <h4 class="text-xl font-bold text-gray-900 mt-4 group-hover:text-emerald-800 transition-colors duration-300">{{ $exam->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $exam->description }}</p>
                                </div>

                                <div class="flex justify-end">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-gray-200 group-hover:border-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                @error('selectedExamId')
                    <p class="text-sm text-rose-600 mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
        @endif

        <!-- STEP 2: SELECT MODE -->
        @if($currentStep === 2)
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-gray-900 font-heading">Select Practice Mode</h3>
                <p class="text-gray-500 text-sm">Choose the session format that fits your learning state.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Practice Mode -->
                    <div wire:click="$set('mode', 'practice')"
                         class="border-2 rounded-2xl p-6 cursor-pointer hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-52
                         {{ $mode === 'practice' ? 'border-emerald-500 bg-emerald-50/40 ring-4 ring-emerald-50' : 'border-gray-200 bg-white hover:border-emerald-200' }}">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Standard Practice</h4>
                            <p class="text-sm text-gray-500 mt-1">Untimed session with customizable sizes. Great for revision.</p>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full self-start">Free Access</span>
                    </div>

                    <!-- Study Mode -->
                    <div wire:click="$set('mode', 'study')"
                         class="border-2 rounded-2xl p-6 cursor-pointer hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-52
                         {{ $mode === 'study' ? 'border-emerald-500 bg-emerald-50/40 ring-4 ring-emerald-50' : 'border-gray-200 bg-white hover:border-emerald-200' }}">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Study Mode</h4>
                            <p class="text-sm text-gray-500 mt-1">Provides instant explanations after answering each question.</p>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full self-start">Free Access</span>
                    </div>

                    <!-- Mock Mode -->
                    <div wire:click="$set('mode', 'mock')"
                         class="relative border-2 rounded-2xl p-6 cursor-pointer hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-52
                         {{ $mode === 'mock' ? 'border-emerald-500 bg-emerald-50/40 ring-4 ring-emerald-50' : 'border-gray-200 bg-white hover:border-emerald-200' }}">
                        
                        <!-- Premium Badge -->
                        <span class="absolute -top-3 -right-3 px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[10px] font-extrabold uppercase tracking-wider rounded-full shadow-md">
                            Premium
                        </span>

                        <div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Timed Exam Mock</h4>
                            <p class="text-sm text-gray-500 mt-1">Simulates real exam board environment. Full-duration countdown.</p>
                        </div>
                        <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full self-start">Requires Premium</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- STEP 3: SELECT SUBJECTS -->
        @if($currentStep === 3)
            <div class="space-y-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 font-heading">Select Curriculum Subjects</h3>
                        <p class="text-gray-500 text-sm mt-1">
                            @if(($selectedExam?->slug ?? '') === 'utme')
                                JAMB UTME requires exactly <strong>4 subjects</strong>. English is compulsory.
                            @else
                                Choose between <strong>1 and 9 subjects</strong> for SSCE.
                            @endif
                        </p>
                    </div>
                    <div class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-2xl font-bold text-sm">
                        Selected: {{ count($selectedSubjects) }}
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($subjects as $subj)
                        <div wire:click="toggleSubject({{ $subj->id }})"
                             class="group border-2 rounded-2xl p-4 cursor-pointer flex items-center space-x-3 transition-all duration-300 hover:shadow-md
                             {{ in_array((string)$subj->id, $selectedSubjects) ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-100 bg-white hover:border-emerald-100' }}">
                            
                            <!-- Checkbox icon replacement -->
                            <span class="w-5 h-5 rounded-md flex items-center justify-center border-2 transition-all duration-300
                                {{ in_array((string)$subj->id, $selectedSubjects) ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-gray-200 text-transparent group-hover:border-emerald-300' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate group-hover:text-emerald-700 transition-colors duration-300">{{ $subj->name }}</p>
                                @if(($selectedExam?->slug ?? '') === 'utme' && $subj->slug === 'english-language')
                                    <span class="text-[9px] font-extrabold text-emerald-600 uppercase tracking-wide">Compulsory</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('selectedSubjects')
                    <p class="text-sm text-rose-600 mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
        @endif

        <!-- STEP 4: FINALIZE -->
        @if($currentStep === 4)
            <div class="space-y-8">
                <h3 class="text-2xl font-bold text-gray-900 font-heading">Finalize Session Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left: Year Setup -->
                    <div class="space-y-4">
                        <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Select Exam Year</label>
                        <p class="text-xs text-gray-500">Pick a specific past year's questions, or randomise for an adaptive test.</p>
                        
                        <select wire:model="year" class="block w-full border-gray-200 rounded-2xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-base py-3 px-4 transition-all duration-300">
                            <option value="random">Adaptive/Random (All Years)</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}">{{ $yr }} Past Questions</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Right: Size Selection (Hidden in Mock Mode) -->
                    <div class="space-y-4">
                        @if($mode === 'mock')
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Exam Parameters</label>
                            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 space-y-2.5">
                                <div class="flex items-center space-x-2 text-blue-800 font-semibold text-sm">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Fixed Full Mock Configuration</span>
                                </div>
                                <p class="text-xs text-blue-700 leading-relaxed">
                                    In Mock Mode, questions and durations match the official exam standard (e.g. 40 questions per subject, 2 hours total).
                                </p>
                            </div>
                        @else
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Questions per Subject</label>
                            <p class="text-xs text-gray-500">Select how many practice questions to generate for each subject.</p>
                            
                            <div class="grid grid-cols-3 gap-4">
                                @foreach([10, 20, 40] as $count)
                                    <div wire:click="$set('questionCount', {{ $count }})"
                                         class="border-2 rounded-2xl py-3 px-4 cursor-pointer text-center hover:border-emerald-300 transition-all duration-300
                                         {{ $questionCount == $count ? 'border-emerald-500 bg-emerald-50/40 font-bold text-emerald-800' : 'border-gray-200 text-gray-600 hover:shadow' }}">
                                        {{ $count }} Qs
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer Actions -->
        <div class="mt-10 pt-6 border-t border-gray-100 flex justify-between items-center">
            @if($currentStep > 1)
                <button type="button" wire:click="prevStep"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 transition-colors duration-300 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Back</span>
                </button>
            @else
                <div></div>
            @endif

            @if($currentStep < 4)
                <button type="button" wire:click="nextStep"
                        class="px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-bold rounded-2xl hover:shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 flex items-center space-x-2">
                    <span>Continue</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            @else
                <button type="button" wire:click="startExam"
                        class="px-10 py-4 bg-gradient-to-r from-emerald-600 to-blue-600 text-white font-extrabold rounded-2xl hover:shadow-xl hover:scale-102 hover:from-emerald-700 hover:to-blue-700 transition-all duration-300 flex items-center space-x-2">
                    <span>Launch CBT Exam</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </button>
            @endif
        </div>
    </div>
</div>
