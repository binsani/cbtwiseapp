<div>
    <x-slot name="header">
        <div>
            <p class="text-xs font-black uppercase tracking-wider text-emerald-700">Institution preparation</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-black text-slate-900 font-heading">Post-UTME practice planner</h1>
            <p class="mt-1 text-sm text-slate-500">Prepare with timed CBT practice, then confirm your school’s current screening rules from its official notice.</p>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-amber-200 bg-amber-50 p-5 sm:p-6">
            <p class="text-[10px] font-black uppercase tracking-wider text-amber-800">Important verification</p>
            <h2 class="mt-1 text-lg font-black text-slate-900">Post-UTME rules vary by institution and can change.</h2>
            <p class="mt-2 text-sm leading-6 text-slate-700">Use CBTWise to practise the appropriate senior-secondary subjects. Before paying, applying, or attending screening, verify the date, eligibility, subject scope and requirements on your chosen institution’s official admissions website.</p>
            <a class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-black text-white hover:bg-slate-800" href="https://ibass.jamb.gov.ng/" target="_blank" rel="noopener noreferrer">Verify course and institution on JAMB IBASS ↗</a>
        </section>

        <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6">
            <div class="mb-5">
                <p class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Set your target</p>
                <h2 class="mt-1 text-lg font-black text-slate-900">Start relevant practice</h2>
                <p class="mt-1 text-xs text-slate-500">Your school and course are used only to guide your setup; CBTWise does not claim they are an official eligibility decision.</p>
            </div>
            <form wire:submit="startPractice" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="block text-sm font-bold text-slate-700">Target institution
                    <input wire:model="institution" class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g. University of Lagos" />
                </label>
                <label class="block text-sm font-bold text-slate-700">Intended course
                    <input wire:model="course" class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g. Medicine and Surgery" />
                </label>
                <div class="sm:col-span-2 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-2">
                    <span class="text-xs text-slate-500">{{ $postUtmeExam ? 'Post-UTME CBT practice is available.' : 'Post-UTME practice bank is being prepared.' }}</span>
                    <button type="submit" @disabled(!$postUtmeExam) class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-black text-white hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-slate-300">Configure Post-UTME Practice &rarr;</button>
                </div>
            </form>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="rounded-2xl bg-white border border-slate-200 p-5"><p class="font-black text-slate-900">1. Verify</p><p class="mt-1 text-xs leading-5 text-slate-500">Check the official institution notice before relying on any date or requirement.</p></div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5"><p class="font-black text-slate-900">2. Practise</p><p class="mt-1 text-xs leading-5 text-slate-500">Use timed CBT sessions and focus on weak syllabus topics.</p></div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5"><p class="font-black text-slate-900">3. Review</p><p class="mt-1 text-xs leading-5 text-slate-500">Use your results to repeat difficult topics before the screening.</p></div>
        </section>
    </div>
</div>
