<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Questions</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ number_format($totalQuestionsInBank) }} questions in bank</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bulk-seeder') }}" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5">
                    <span>⚡</span> Bulk Seeder
                </a>
                <button wire:click="openCreateForm" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-sm shadow-emerald-600/10 transition-colors flex items-center gap-1.5">
                    <span>+</span> Add Question
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Search and Filter Bar -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="relative w-full md:max-w-xs">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search questions..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <!-- Subject Filter -->
                <select wire:model.live="subjectFilter" class="px-3.5 py-2 border border-slate-200 rounded-2xl text-xs font-bold bg-white text-slate-700 focus:border-emerald-500">
                    <option value="all">All Subjects</option>
                    @foreach($allFilterSubjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>

                <!-- Exam Buttons Filters -->
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['all', 'UTME', 'WAEC', 'NECO'] as $type)
                        <button wire:click="$set('examFilter', '{{ $type }}')" class="px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $examFilter === $type ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                            {{ $type === 'all' ? 'All' : $type }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-8">
                                <input type="checkbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Question</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Exam</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Subject</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-20">Year</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Difficulty</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-28 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($questions as $q)
                            <tr class="hover:bg-slate-50/40 transition-colors {{ $q->is_flagged ? 'bg-amber-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($q->is_flagged)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-red-100 text-red-700">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-semibold">
                                    {{ $q->subject->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-semibold">
                                    {{ $q->year ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $diffClass = [
                                            'easy' => 'text-emerald-600 font-bold',
                                            'medium' => 'text-amber-600 font-bold',
                                            'hard' => 'text-red-500 font-bold'
                                        ][$q->difficulty ?? 'easy'] ?? 'text-slate-600';
                                    @endphp
                                    <span class="{{ $diffClass }}">{{ $q->difficulty ?? 'easy' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Flag Toggle Button -->
                                        <button wire:click="toggleFlag({{ $q->id }})" class="p-1.5 {{ $q->is_flagged ? 'text-red-600 bg-red-50' : 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' }} rounded-lg transition-colors" title="{{ $q->is_flagged ? 'Remove Flag' : 'Flag Question' }}">
                                            <svg class="w-4 h-4" fill="{{ $q->is_flagged ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                        </button>
                                        <!-- Edit Button -->
                                        <button wire:click="openEditForm({{ $q->id }})" class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <!-- Delete Button -->
                                        <button wire:click="deleteQuestion({{ $q->id }})" wire:confirm="Are you sure you want to delete this question?" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-400">
                                    No questions found in database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($questions->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>

        <!-- Question Form Dialog (Modal) -->
        @if ($isFormOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    <!-- Background Backdrop -->
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeForm"></div>

                    <!-- Modal bounds container -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                                <h3 class="text-lg font-bold text-slate-950 font-heading" id="modal-title">
                                    {{ $isEditMode ? 'Edit Question' : 'Add Question' }}
                                </h3>
                                <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form wire:submit.prevent="saveQuestion" class="space-y-4 mt-4 text-slate-700">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Exam *</label>
                                        <select wire:model.live="exam_id" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">Select Exam</option>
                                            @foreach($exams as $ex)
                                                <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('exam_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Subject *</label>
                                        <select wire:model.live="subject_id" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $sb)
                                                <option value="{{ $sb->id }}">{{ $sb->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subject_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Topic</label>
                                        <select wire:model="topic_id" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">Select Topic</option>
                                            @foreach($topics as $tp)
                                                <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('topic_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Year</label>
                                        <input wire:model="year" type="number" placeholder="e.g. 2024" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                        @error('year') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Difficulty *</label>
                                        <select wire:model="difficulty" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="easy">Easy</option>
                                            <option value="medium">Medium</option>
                                            <option value="hard">Hard</option>
                                        </select>
                                        @error('difficulty') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Question Content *</label>
                                    <textarea wire:model="question_text" rows="3" placeholder="Enter question description..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    @error('question_text') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Option A *</label>
                                        <input wire:model="option_a" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500" />
                                        @error('option_a') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Option B *</label>
                                        <input wire:model="option_b" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500" />
                                        @error('option_b') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Option C *</label>
                                        <input wire:model="option_c" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500" />
                                        @error('option_c') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Option D *</label>
                                        <input wire:model="option_d" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500" />
                                        @error('option_d') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Option E (Optional)</label>
                                        <input wire:model="option_e" type="text" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500" />
                                        @error('option_e') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Correct Answer *</label>
                                        <select wire:model="correct_option" class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="a">Option A</option>
                                            <option value="b">Option B</option>
                                            <option value="c">Option C</option>
                                            <option value="d">Option D</option>
                                            <option value="e">Option E</option>
                                        </select>
                                        @error('correct_option') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Explanation</label>
                                    <textarea wire:model="explanation" rows="2" placeholder="Explain the rationale behind the correct option..." class="w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm focus:border-emerald-500"></textarea>
                                    @error('explanation') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="closeForm" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                        {{ $isEditMode ? 'Update' : 'Create' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
</div>
