<div class="dashboard-shell max-w-5xl font-sans space-y-6 sm:space-y-8">
    
    <!-- Navigation Tabs -->
    <x-dashboard-nav />

    <!-- Header -->
    <div class="dashboard-page-header">
        <div>
            <h1 class="dashboard-page-title font-heading">Bookmarked Questions</h1>
            <p class="dashboard-page-subtitle">Review questions you saved during practice sessions.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50 transition-colors">
            &larr; Back to Dashboard
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-bold shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filter and Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center gap-3">
        <div class="flex-1 w-full">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search bookmarked questions..." 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-emerald-500">
        </div>
        <div class="w-full sm:w-56">
            <select wire:model.live="subjectFilter" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                <option value="">All Subjects</option>
                @foreach($availableSubjects as $subj)
                    <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Bookmarks Grid -->
    <div class="space-y-6">
        @forelse($bookmarks as $bookmark)
            @if($bookmark->question)
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm space-y-4 relative overflow-hidden">
                    
                    <!-- Question Header metadata -->
                    <div class="flex flex-wrap justify-between items-center gap-2 border-b border-slate-50 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[10px] font-black uppercase border border-emerald-100">
                                {{ $bookmark->question->exam?->name ?? 'General' }}
                            </span>
                            <span class="px-2 py-0.5 bg-slate-55 text-slate-500 rounded text-[10px] font-bold uppercase">
                                {{ $bookmark->question->subject?->name ?? 'Subject' }}
                            </span>
                            @if($bookmark->question->year)
                                <span class="text-slate-400 text-xs font-bold">Year {{ $bookmark->question->year }}</span>
                            @endif
                        </div>
                        
                        <button 
                            wire:click="removeBookmark({{ $bookmark->question_id }})" 
                            class="text-xs font-extrabold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-xl transition-colors"
                        >
                            Remove Bookmark
                        </button>
                    </div>

                    <!-- Question Text -->
                    <div class="text-slate-800 text-sm leading-relaxed">
                        {!! $bookmark->question->question_text !!}
                        @if($bookmark->question->question_image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $bookmark->question->question_image) }}" alt="Question Image" class="max-h-48 rounded-xl">
                            </div>
                        @endif
                    </div>

                    <!-- Options display with correct option highlighted -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                            @if($bookmark->question->{'option_' . $opt})
                                <div class="p-3 border rounded-xl flex items-center space-x-3 text-xs font-semibold
                                    {{ $bookmark->question->correct_option === $opt ? 'border-emerald-500 bg-emerald-50/20 text-emerald-800 font-bold' : 'border-slate-100 bg-slate-50 text-slate-600' }}">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center border text-[9px] font-bold uppercase
                                        {{ $bookmark->question->correct_option === $opt ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-200 text-slate-450' }}">
                                        {{ $opt }}
                                    </span>
                                    <span>{!! $bookmark->question->{'option_' . $opt} !!}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Local Explanation if exists -->
                    @if($bookmark->question->explanation)
                        <div class="mt-4 bg-slate-50 rounded-2xl border border-slate-100 p-4 text-xs">
                            <span class="font-bold text-slate-800 block mb-1">Explanation:</span>
                            <p class="text-slate-600 leading-relaxed">{!! nl2br(e($bookmark->question->explanation)) !!}</p>
                        </div>
                    @endif
                </div>
            @endif
        @empty
            <div class="text-center py-12 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <span class="text-3xl block mb-2">🔖</span>
                <p class="text-slate-500 text-sm">You haven't bookmarked any questions yet.</p>
                <a href="{{ route('exam.setup') }}" class="inline-block mt-4 text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Start Practice CBT &rarr;
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookmarks->links() }}
    </div>
</div>
