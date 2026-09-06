<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Exams & Subjects</h1>
                <p class="text-xs text-slate-500 mt-0.5">Manage exam types and their subjects</p>
            </div>
            
            @if ($activeTab === 'exams')
                <button wire:click="openAddExamModal" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl text-xs font-bold shadow-sm shadow-emerald-700/10 transition-colors flex items-center gap-1.5 self-end sm:self-auto">
                    <span>+</span> Add Exam
                </button>
            @else
                <button wire:click="openAddSubjectModal" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl text-xs font-bold shadow-sm shadow-emerald-700/10 transition-colors flex items-center gap-1.5 self-end sm:self-auto">
                    <span>+</span> Add Subject
                </button>
            @endif
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Tab Bar -->
        <div class="flex bg-slate-100/60 p-1 rounded-2xl w-fit">
            <button wire:click="changeTab('exams')" class="px-5 py-2 text-xs font-extrabold rounded-xl transition-all {{ $activeTab === 'exams' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                Exams ({{ $examsCount }})
            </button>
            <button wire:click="changeTab('subjects')" class="px-5 py-2 text-xs font-extrabold rounded-xl transition-all {{ $activeTab === 'subjects' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                Subjects ({{ $subjectsCount }})
            </button>
        </div>

        <!-- Tab 1: Exams Table -->
        @if ($activeTab === 'exams')
            <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Subjects</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Active</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($exams as $ex)
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                        {{ $ex->name }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                        {{ $ex->slug }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 max-w-xs truncate">
                                        {{ $ex->description ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-600">
                                        {{ $ex->subjects_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($ex->is_active)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Yes</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">No</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2.5">
                                            <button wire:click="openEditExamModal({{ $ex->id }})" class="text-slate-500 hover:text-emerald-700 transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button wire:click="deleteExam({{ $ex->id }})" wire:confirm="Are you sure you want to delete this exam? All related subjects and questions will be deleted." class="text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                        No exams registered.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($exams->hasPages())
                    <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                        {{ $exams->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Tab 2: Subjects Table -->
        @if ($activeTab === 'subjects')
            <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-12">Icon</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Exam</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Active</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($subjects as $sb)
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="px-6 py-4 text-lg">
                                        {{ $sb->icon ?? '📚' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                        {{ $sb->name }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                        {{ $sb->slug }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $sb->exam->slug === 'utme' ? 'bg-emerald-50 text-emerald-700' : ($sb->exam->slug === 'waec' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700') }}">
                                            {{ $sb->exam->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-600">
                                        {{ $sb->sort_order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($sb->is_active)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Yes</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">No</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2.5">
                                            <button wire:click="openEditSubjectModal({{ $sb->id }})" class="text-slate-500 hover:text-emerald-700 transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button wire:click="deleteSubject({{ $sb->id }})" wire:confirm="Are you sure you want to delete this subject? All related questions will be deleted." class="text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-400">
                                        No subjects registered.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($subjects->hasPages())
                    <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                        {{ $subjects->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Exam Modal -->
        @if ($isExamModalOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="exam-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isExamModalOpen', false)"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                                <h3 class="text-lg font-bold text-slate-950 font-heading" id="exam-modal-title">
                                    {{ $isEditMode ? 'Edit Exam' : 'Add Exam' }}
                                </h3>
                                <button wire:click="$set('isExamModalOpen', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form wire:submit.prevent="saveExam" class="space-y-4 mt-4 text-slate-700">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Exam Name *</label>
                                    <input wire:model="exam_name" type="text" placeholder="e.g. Unified Tertiary Matriculation Examination" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                    @error('exam_name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Slug (URL Name) *</label>
                                    <input wire:model="exam_slug" type="text" placeholder="e.g. utme" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                    @error('exam_slug') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Description</label>
                                    <textarea wire:model="exam_description" rows="3" placeholder="Enter details about this exam standard..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    @error('exam_description') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Default Duration (Min) *</label>
                                        <input wire:model="exam_duration_minutes_default" type="number" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                        @error('exam_duration_minutes_default') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Questions / Subject *</label>
                                        <input wire:model="exam_questions_per_subject_default" type="number" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                        @error('exam_questions_per_subject_default') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Is Active *</label>
                                    <select wire:model="exam_is_active" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    @error('exam_is_active') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="$set('isExamModalOpen', false)" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                        {{ $isEditMode ? 'Update' : 'Create' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Subject Modal -->
        @if ($isSubjectModalOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="subject-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isSubjectModalOpen', false)"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                                <h3 class="text-lg font-bold text-slate-950 font-heading" id="subject-modal-title">
                                    {{ $isEditMode ? 'Edit Subject' : 'Add Subject' }}
                                </h3>
                                <button wire:click="$set('isSubjectModalOpen', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form wire:submit.prevent="saveSubject" class="space-y-4 mt-4 text-slate-700">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Exam Standard *</label>
                                    <select wire:model="subject_exam_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="">Select Exam</option>
                                        @foreach($allExams as $ex)
                                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('subject_exam_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Subject Name *</label>
                                    <input wire:model="subject_name" type="text" placeholder="e.g. Biology" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                    @error('subject_name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Slug (URL Name) *</label>
                                    <input wire:model="subject_slug" type="text" placeholder="e.g. biology" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                    @error('subject_slug') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Icon / Emoji</label>
                                        <input wire:model="subject_icon" type="text" placeholder="📚" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                        @error('subject_icon') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sort Order *</label>
                                        <input wire:model="subject_sort_order" type="number" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                        @error('subject_sort_order') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Is Active *</label>
                                    <select wire:model="subject_is_active" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    @error('subject_is_active') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" wire:click="$set('isSubjectModalOpen', false)" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
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
