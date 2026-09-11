<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Question Bank</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ number_format($totalQuestionsInBank) }} verified questions &bull; <span class="text-rose-600 font-bold">{{ number_format($flaggedCount) }} flagged</span></p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="exportCsv" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export CSV</span>
                </button>
                <a href="{{ route('admin.bulk-seeder') }}" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5">
                    <span>⚡</span> Bulk Seeder
                </a>
                <button wire:click="openCreateForm" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-sm shadow-emerald-600/10 transition-colors flex items-center gap-1.5">
                    <span>+</span> Add Question
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Bulk Action Banner -->
        @if(!empty($selectedQuestions))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-xs font-bold text-emerald-900">{{ count($selectedQuestions) }} question(s) selected</span>
                <div class="flex items-center gap-2">
                    <button wire:click="bulkFlag" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-sm">
                        Flag Selected
                    </button>
                    <button wire:click="bulkUnflag" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-sm">
                        Unflag Selected
                    </button>
                    <button wire:click="bulkDelete" wire:confirm="Are you sure you want to permanently delete all selected questions?" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm">
                        Delete Selected
                    </button>
                </div>
            </div>
        @endif

        <!-- Search and Filter Bar -->
        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="relative sm:col-span-2">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by question text or explanation..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs font-medium transition-colors outline-none" />
                </div>

                <div>
                    <select wire:model.live="subjectFilter" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:border-emerald-500 outline-none">
                        <option value="all">All Subjects</option>
                        @foreach($allFilterSubjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select wire:model.live="difficultyFilter" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:border-emerald-500 outline-none">
                        <option value="all">All Difficulties</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            <!-- Exam Buttons & Flag filter -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['all', 'UTME', 'WAEC', 'NECO'] as $type)
                        <button wire:click="$set('examFilter', '{{ $type }}')" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $examFilter === $type ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $type === 'all' ? 'All Exams' : $type }}
                        </button>
                    @endforeach
                </div>

                <div class="flex items-center gap-2">
                    <select wire:model.live="flaggedFilter" class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 bg-white focus:border-emerald-500 outline-none">
                        <option value="all">All Status</option>
                        <option value="flagged">Flagged Only</option>
                        <option value="unflagged">Clean Only</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4 w-8">
                                <input type="checkbox" wire:model.live="selectAll" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                            </th>
                            <th class="px-6 py-4">Question</th>
                            <th class="px-6 py-4 w-24">Exam</th>
                            <th class="px-6 py-4 w-36">Subject</th>
                            <th class="px-6 py-4 w-20">Year</th>
                            <th class="px-6 py-4 w-24">Difficulty</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($questions as $q)
                            <tr class="hover:bg-slate-50/40 transition-colors {{ $q->is_flagged ? 'bg-amber-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox" wire:model.live="selectedQuestions" value="{{ $q->id }}" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($q->is_flagged)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-rose-100 text-rose-700">
                                                FLAGGED
                                            </span>
                                        @endif
                                        <div class="text-sm font-medium text-slate-800 line-clamp-2">
                                            {{ strip_tags($q->question_text) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $q->exam->slug === 'utme' ? 'bg-emerald-50 text-emerald-700' : ($q->exam->slug === 'waec' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700') }}">
                                        {{ $q->exam->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-700">
                                    {{ $q->subject->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                    {{ $q->year ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $diffClass = [
                                            'easy' => 'text-emerald-600 font-bold',
                                            'medium' => 'text-amber-600 font-bold',
                                            'hard' => 'text-rose-600 font-bold'
                                        ][$q->difficulty ?? 'easy'] ?? 'text-slate-600';
                                    @endphp
                                    <span class="{{ $diffClass }} capitalize">{{ $q->difficulty ?? 'easy' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Flag Toggle Button -->
                                        <button wire:click="toggleFlag({{ $q->id }})" class="p-1.5 {{ $q->is_flagged ? 'text-rose-600 bg-rose-50' : 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' }} rounded-lg transition-colors" title="{{ $q->is_flagged ? 'Remove Flag' : 'Flag Question' }}">
                                            <svg class="w-4 h-4" fill="{{ $q->is_flagged ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                        </button>
                                        <!-- Duplicate Button -->
                                        <button wire:click="duplicateQuestion({{ $q->id }})" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Duplicate Question">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                        <!-- Edit Button -->
                                        <button wire:click="openEditForm({{ $q->id }})" class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit Question">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <!-- Delete Button -->
                                        <button wire:click="deleteQuestion({{ $q->id }})" wire:confirm="Are you sure you want to delete this question?" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Question">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400 text-xs">
                                    No questions found matching criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($questions->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>

        <!-- Question Form Dialog (Modal) -->
        @if ($isFormOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <h3 class="text-base font-black text-slate-900 font-heading">
                            {{ $isEditMode ? 'Edit Question' : 'Add Question' }}
                        </h3>
                        <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <form wire:submit.prevent="saveQuestion" class="space-y-4 text-slate-700 text-xs">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Exam *</label>
                                <select wire:model.live="exam_id" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500">
                                    <option value="">Select Exam</option>
                                    @foreach($exams as $ex)
                                        <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                    @endforeach
                                </select>
                                @error('exam_id') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Subject *</label>
                                <select wire:model.live="subject_id" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $sb)
                                        <option value="{{ $sb->id }}">{{ $sb->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Topic</label>
                                <select wire:model="topic_id" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500">
                                    <option value="">Select Topic</option>
                                    @foreach($topics as $tp)
                                        <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Year</label>
                                <input wire:model="year" type="number" placeholder="e.g. 2024" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500" />
                            </div>

                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Difficulty *</label>
                                <select wire:model="difficulty" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500">
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Question Content *</label>
                            <textarea wire:model="question_text" rows="3" placeholder="Enter question description..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-emerald-500"></textarea>
                            @error('question_text') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Option A *</label>
                                <input wire:model="option_a" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500" />
                                @error('option_a') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Option B *</label>
                                <input wire:model="option_b" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500" />
                                @error('option_b') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Option C *</label>
                                <input wire:model="option_c" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500" />
                                @error('option_c') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Option D *</label>
                                <input wire:model="option_d" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500" />
                                @error('option_d') <span class="text-[11px] text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Option E (Optional)</label>
                                <input wire:model="option_e" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500" />
                            </div>
                            <div>
                                <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Correct Answer *</label>
                                <select wire:model="correct_option" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500">
                                    <option value="a">Option A</option>
                                    <option value="b">Option B</option>
                                    <option value="c">Option C</option>
                                    <option value="d">Option D</option>
                                    <option value="e">Option E</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-500 uppercase tracking-wider mb-1">Explanation</label>
                            <textarea wire:model="explanation" rows="2" placeholder="Explain the rationale behind the correct option..." class="w-full px-3.5 py-2 border border-slate-200 rounded-xl focus:border-emerald-500"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="closeForm" class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl font-bold">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm">
                                {{ $isEditMode ? 'Update Question' : 'Create Question' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </main>
</div>
