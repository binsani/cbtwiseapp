<div>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-1">
                    <span>JAMB UTME Official Guide</span>
                    <span>&bull;</span>
                    <span>Brochure Checker</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading">
                    JAMB Course and Subject Combination Checker
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Instant lookup of compulsory JAMB 4-subject combinations, O'Level subject prerequisites, and top Nigerian universities.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('exam.setup', ['exam' => 'utme']) }}" 
                   class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs sm:text-sm font-black shadow-sm transition-all flex items-center gap-1.5">
                    <span>Practice UTME Now</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Search & Filter Controls -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Search course (e.g. Medicine, Computer Science, Law, Accounting) or subject..." 
                           class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400">
                    @if($search)
                        <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                            &times;
                        </button>
                    @endif
                </div>
            </div>

            <!-- Faculty Filters -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2.5">Filter by Faculty</span>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="selectFaculty('')" 
                            class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ empty($selectedFaculty) ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All Faculties ({{ count($faculties) }})
                    </button>
                    @foreach($faculties as $key => $faculty)
                        <button wire:click="selectFaculty('{{ $key }}')" 
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $selectedFaculty === $key ? 'bg-emerald-600 text-white shadow-sm font-extrabold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            <span>{{ $faculty['icon'] }}</span>
                            <span>{{ $faculty['name'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Selected Course Modal / Detail Hero -->
        @if($activeCourse)
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden border border-emerald-500/20">
                <button wire:click="clearSelectedCourse" class="absolute top-4 right-4 text-slate-400 hover:text-white p-2 rounded-xl bg-white/10 text-xs font-bold transition-colors">
                    Close Details &times;
                </button>

                <div class="max-w-4xl space-y-6">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">{{ $activeCourse['faculty_icon'] }}</span>
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-400 bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-500/30">
                            {{ $activeCourse['faculty_name'] }}
                        </span>
                    </div>

                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black font-heading text-white">
                            {{ $activeCourse['name'] }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-300 mt-1">{{ $activeCourse['remarks'] }}</p>
                    </div>

                    <!-- 4 Required Subjects Grid -->
                    <div>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-emerald-300 mb-3">
                            Compulsory JAMB UTME 4-Subject Combination
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach($activeCourse['utme_subjects'] as $subj)
                                <div class="bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl p-4 flex flex-col justify-between">
                                    <span class="text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-wider">Subject {{ $loop->iteration }}</span>
                                    <span class="text-sm sm:text-base font-black text-white mt-1">{{ $subj }}</span>
                                    @if($subj === 'English Language')
                                        <span class="text-[9px] font-black text-emerald-300 uppercase mt-2">Compulsory for all</span>
                                    @else
                                        <span class="text-[9px] text-slate-400 mt-2">Prerequisite</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- O'Level & Institutions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-1 flex items-center gap-1.5">
                                <span>📋</span>
                                <span>O'Level (WAEC / NECO / NABTEB) Requirements</span>
                            </h4>
                            <p class="text-xs text-slate-300 leading-relaxed">{{ $activeCourse['olevel_requirements'] }}</p>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-1 flex items-center gap-1.5">
                                <span>🏛️</span>
                                <span>Accredited Universities Offering Course</span>
                            </h4>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @foreach($activeCourse['institutions'] as $inst)
                                    <span class="px-2.5 py-1 bg-white/10 rounded-lg text-xs font-bold text-white border border-white/10">
                                        {{ $inst }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- One-Click Practice Launch -->
                    <div class="pt-4 border-t border-white/10 flex flex-wrap items-center gap-3">
                        <a href="{{ route('exam.setup', ['exam' => 'utme', 'course' => $activeCourse['slug']]) }}" 
                           class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-xs sm:text-sm font-black shadow-lg transition-all flex items-center gap-2">
                            <span>Launch 4-Subject UTME Practice For {{ $activeCourse['name'] }}</span>
                            <span>⚡</span>
                        </a>
                        <button wire:click="clearSelectedCourse" 
                                class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl text-xs font-bold transition-colors">
                            Back to All Courses
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Courses Listing -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-extrabold text-slate-900 font-heading">
                    Matching Courses ({{ count($courses) }})
                </h3>
                <span class="text-xs text-slate-400">Click any course to inspect prerequisites</span>
            </div>

            @if(count($courses) === 0)
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm space-y-3">
                    <span class="text-4xl">🔍</span>
                    <h4 class="text-base font-bold text-slate-900">No courses matched your query</h4>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        Try searching by course keyword (e.g. "Law", "Medicine", "Civil", "Accounting") or clear the faculty filter.
                    </p>
                    <button wire:click="$set('search', ''); $set('selectedFaculty', '');" 
                            class="px-4 py-2 bg-emerald-50 text-emerald-700 font-bold rounded-xl text-xs hover:bg-emerald-100 transition-colors">
                        Reset Filters
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($courses as $course)
                        <div wire:click="selectCourse('{{ $course['slug'] }}')"
                             class="group bg-white rounded-3xl p-6 border-2 cursor-pointer transition-all duration-300 hover:shadow-lg flex flex-col justify-between
                             {{ $selectedCourseSlug === $course['slug'] ? 'border-emerald-500 bg-emerald-50/20 shadow-md ring-4 ring-emerald-50' : 'border-slate-200/80 hover:border-emerald-300' }}">
                            
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xl">{{ $course['faculty_icon'] }}</span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full group-hover:bg-emerald-100 group-hover:text-emerald-800 transition-colors">
                                        {{ $course['faculty_name'] }}
                                    </span>
                                </div>

                                <h4 class="text-base font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors font-heading leading-snug">
                                    {{ $course['name'] }}
                                </h4>

                                <!-- Subject combination badges -->
                                <div class="mt-4">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mb-1.5">JAMB 4 Subjects:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($course['utme_subjects'] as $subj)
                                            <span class="px-2 py-1 bg-slate-100 text-slate-700 group-hover:bg-emerald-50 group-hover:text-emerald-800 rounded-lg text-[11px] font-bold transition-colors">
                                                {{ $subj }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="font-extrabold text-emerald-600 group-hover:underline flex items-center gap-1">
                                    <span>View Requirements</span>
                                    <span>&rarr;</span>
                                </span>
                                <span class="text-[11px] text-slate-400 font-medium">
                                    {{ count($course['institutions']) }}+ Institutions
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
