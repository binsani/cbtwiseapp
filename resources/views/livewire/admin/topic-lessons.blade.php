<div class="space-y-6">
    <div><p class="text-xs font-black uppercase tracking-wider text-emerald-700">Content review</p><h1 class="mt-1 text-2xl font-black text-slate-900">Topic lessons</h1><p class="mt-1 text-sm text-slate-500">Add only reviewed, authorised learning material. Drafts stay hidden from students.</p></div>
    @if(session('message'))<div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">{{ session('message') }}</div>@endif
    <form wire:submit="save" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-5">
        <label class="block text-sm font-bold text-slate-700">Topic
            <select wire:model.live="topicId" class="mt-1.5 block w-full rounded-xl border-slate-200"><option value="">Select a topic</option>@foreach($topics as $topic)<option value="{{ $topic->id }}">{{ $topic->subject->exam->name }} — {{ $topic->subject->name }} — {{ $topic->name }}</option>@endforeach</select>
        </label>
        <label class="block text-sm font-bold text-slate-700">Lesson title<input wire:model="title" class="mt-1.5 block w-full rounded-xl border-slate-200" /></label>
        <label class="block text-sm font-bold text-slate-700">Reviewed lesson notes<textarea wire:model="lessonNotes" rows="10" class="mt-1.5 block w-full rounded-xl border-slate-200"></textarea></label>
        <div class="grid sm:grid-cols-2 gap-4"><label class="block text-sm font-bold text-slate-700">Worked example question<textarea wire:model="exampleQuestion" rows="5" class="mt-1.5 block w-full rounded-xl border-slate-200"></textarea></label><label class="block text-sm font-bold text-slate-700">Worked solution<textarea wire:model="exampleSolution" rows="5" class="mt-1.5 block w-full rounded-xl border-slate-200"></textarea></label></div>
        <div class="flex flex-wrap items-center justify-between gap-3"><select wire:model="status" class="rounded-xl border-slate-200"><option value="draft">Save as draft</option><option value="published">Publish to students</option></select><button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-black text-white hover:bg-emerald-700">Save lesson</button></div>
    </form>
</div>
