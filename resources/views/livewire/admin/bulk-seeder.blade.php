<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Bulk Question Seeder</h1>
                <p class="text-xs text-slate-500 mt-0.5">Automated ALOC import engine with duplicate prevention and coverage balancing</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Low Question Coverage Warning Alert -->
        @if($lowCoverageSubjects->count() > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 shadow-sm space-y-3">
                <div class="flex items-center gap-2 text-amber-900 font-black text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Low Question Coverage Warning ({{ $lowCoverageSubjects->count() }} subjects critically low)</span>
                </div>
                <p class="text-xs text-amber-800">The following subjects currently have fewer than 50 local questions. Target these subjects in the seeder below:</p>
                <div class="flex flex-wrap gap-2 pt-1">
                    @foreach($lowCoverageSubjects->take(8) as $lowSub)
                        <button wire:click="$set('selectedSubjectId', {{ $lowSub->id }})" class="px-3 py-1 bg-white border border-amber-300 text-amber-900 rounded-xl text-xs font-bold hover:bg-amber-100 transition-colors flex items-center gap-1.5 shadow-2xs">
                            <span>{{ $lowSub->name }} ({{ $lowSub->exam->slug }})</span>
                            <span class="px-1.5 py-0.2 bg-amber-200 text-amber-900 text-[10px] rounded-full">{{ $lowSub->questions_count }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Seeder Configuration Card -->
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm p-6 sm:p-8 space-y-6">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Import Job Configuration</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Target Examination</label>
                    <select wire:model.live="selectedExamId" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        <option value="all">All Examinations (UTME, WAEC, NECO)</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Target Subject</label>
                    <select wire:model.live="selectedSubjectId" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        <option value="all">All Subjects in selected exam</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->exam->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Batches per Subject</label>
                    <select wire:model="batches" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        <option value="1">1 Batch (~15 questions)</option>
                        <option value="2">2 Batches (~30 questions)</option>
                        <option value="3">3 Batches (~45 questions)</option>
                        <option value="5">5 Batches (~75 questions)</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="dryRun" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                    <span class="text-xs font-bold text-slate-700">Dry Run (Analyze without saving to database)</span>
                </div>

                <button wire:click="startBulkFetch" wire:loading.attr="disabled" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl text-xs shadow-sm shadow-emerald-600/20 transition-all flex items-center gap-2">
                    <span wire:loading.remove>⚡ Start Bulk Fetch</span>
                    <span wire:loading class="flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Importing from ALOC API...
                    </span>
                </button>
            </div>
        </div>

        <!-- Live Metrics & Log Output -->
        @if(!empty($logs) || $totalScanned > 0)
            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-white border border-slate-100 rounded-2xl text-center shadow-xs">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Questions Scanned</p>
                        <h4 class="text-xl font-black text-slate-900 mt-1">{{ $totalScanned }}</h4>
                    </div>
                    <div class="p-4 bg-white border border-slate-100 rounded-2xl text-center shadow-xs">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">New Questions Created</p>
                        <h4 class="text-xl font-black text-emerald-600 mt-1">+{{ $totalCreated }}</h4>
                    </div>
                    <div class="p-4 bg-white border border-slate-100 rounded-2xl text-center shadow-xs">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Duplicates Skipped</p>
                        <h4 class="text-xl font-black text-amber-600 mt-1">{{ $totalSkippedDuplicates }}</h4>
                    </div>
                </div>

                <div class="bg-slate-950 text-emerald-400 font-mono text-xs rounded-3xl p-6 shadow-md max-h-72 overflow-y-auto space-y-1">
                    <div class="text-slate-500 font-bold border-b border-slate-800 pb-2 mb-2">LIVE SEEDER TERMINAL OUTPUT</div>
                    @foreach($logs as $log)
                        <div>{{ $log }}</div>
                    @endforeach
                </div>
            </div>
        @endif

    </main>
</div>
