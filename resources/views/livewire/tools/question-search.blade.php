<div>
    <x-slot name="header">
        <div>
            <p class="text-xs font-black uppercase tracking-wider text-emerald-700">Revision library</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-black text-slate-900 font-heading">Search practice questions</h1>
            <p class="mt-1 text-sm text-slate-500">Find relevant past-question topics, then practise them in CBT mode.</p>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input wire:model.live.debounce.350ms="search" type="search" placeholder="Search a word or phrase..." class="lg:col-span-1 w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" />
                <select wire:model.live="selectedExamId" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    @foreach($exams as $exam)<option value="{{ $exam->id }}">{{ $exam->name }}</option>@endforeach
                </select>
                <select wire:model.live="selectedSubjectId" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">All subjects</option>
                    @foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach
                </select>
                <select wire:model.live="selectedYear" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">All years</option>
                    @foreach($years as $year)<option value="{{ $year }}">{{ $year }}</option>@endforeach
                </select>
            </div>
        </section>

        <p class="text-xs text-slate-500">Questions are shown for revision only; answers remain available through a completed practice session.</p>

        <div class="space-y-3">
            @forelse($questions as $question)
                <article class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-black uppercase tracking-wider text-slate-500">
                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ $question->exam->name }}</span>
                        <span>{{ $question->subject->name }}</span>
                        @if($question->year)<span>&bull; {{ $question->year }}</span>@endif
                        @if($question->topic)<span class="text-emerald-700">&bull; {{ $question->topic->name }}</span>@endif
                    </div>
                    <p class="mt-3 text-sm sm:text-base font-semibold leading-relaxed text-slate-800">{{ \Illuminate\Support\Str::limit(strip_tags($question->question_text), 240) }}</p>
                    <div class="mt-4">
                        <a href="{{ route('exam.setup', $question->topic ? ['topic' => $question->topic->id] : ['exam' => $question->exam->slug, 'subject' => $question->subject->id]) }}" class="inline-flex rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-black text-white hover:bg-emerald-700">Practice this area &rarr;</a>
                    </div>
                </article>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center text-sm text-slate-500">No matching questions found. Try another keyword or filter.</div>
            @endforelse
        </div>
        {{ $questions->links() }}
    </div>
</div>
