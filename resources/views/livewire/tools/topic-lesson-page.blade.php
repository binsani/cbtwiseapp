<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
    <a href="{{ route('topic.practice', ['exam' => $topic->subject->exam->slug, 'subject' => $topic->subject->id]) }}" class="text-xs font-black text-emerald-700">&larr; Back to topics</a>
    <section class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
        <p class="text-[10px] font-black uppercase tracking-wider text-emerald-700">{{ $topic->subject->exam->name }} &middot; {{ $topic->subject->name }}</p>
        <h1 class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 font-heading">{{ $lesson?->title ?? $topic->name }}</h1>
        @if($lesson)
            <div class="mt-6 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $lesson->lesson_notes }}</div>
            @if($lesson->worked_example_question)
                <section class="mt-8 rounded-2xl border border-emerald-200 bg-emerald-50/60 p-5"><p class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Worked example</p><p class="mt-2 font-bold text-slate-900">{{ $lesson->worked_example_question }}</p><p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $lesson->worked_example_solution }}</p></section>
            @endif
        @else
            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-slate-700">Reviewed learning notes for this topic are being prepared. You can still practise the tagged questions now.</div>
        @endif
        <a href="{{ route('exam.setup', ['exam' => $topic->subject->exam->slug, 'subject' => $topic->subject->id, 'topic' => $topic->id]) }}" class="mt-7 inline-flex rounded-xl bg-emerald-600 px-5 py-3 text-sm font-black text-white">Practise this topic &rarr;</a>
    </section>
</div>
