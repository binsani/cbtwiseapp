@extends('layouts.public')

@section('title', $exam->name . ' CBT Simulator — CBTWise')
@section('meta_description', 'Prepare for the ' . $exam->name . ' CBT with simulated tests, topic-wise practice, and analytics. Access ' . number_format($totalQuestions) . ' official past questions.')

@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "{{ $exam->name }} prep on CBTWise",
  "url": "{{ route('exams.show', $exam->slug) }}",
  "description": "Realistic exam simulation for {{ $exam->name }} containing {{ $totalQuestions }} active practice questions."
}
</script>
@endsection

@section('content')
<!-- Header Banner -->
<section class="py-16 bg-gradient-to-b from-emerald-50/20 via-white to-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('exams.index') }}" class="ml-1 hover:text-emerald-600 transition-colors md:ml-2">Exams</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-slate-400 md:ml-2">{{ $exam->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">
                    Exam Portal
                </span>
                <h1 class="text-4xl sm:text-5xl font-black text-slate-950 font-heading tracking-tight">
                    {{ $exam->name }} CBT Practice
                </h1>
                <p class="text-slate-600 text-lg sm:text-xl leading-relaxed">
                    {{ $exam->description ?? 'Get complete access to official syllabus past questions and simulated mock examinations built specifically to mimic official standards.' }}
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4">
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="text-slate-400 text-xs font-semibold uppercase">Total Database</div>
                        <div class="text-2xl font-black text-slate-900 font-heading mt-1">{{ number_format($totalQuestions) }} Questions</div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="text-slate-400 text-xs font-semibold uppercase">Year Range</div>
                        <div class="text-2xl font-black text-slate-900 font-heading mt-1">
                            @if($yearRange->min_year && $yearRange->max_year)
                                {{ $yearRange->min_year }} - {{ $yearRange->max_year }}
                            @else
                                All Years
                            @endif
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm col-span-2 sm:col-span-1">
                        <div class="text-slate-400 text-xs font-semibold uppercase">Subjects Included</div>
                        <div class="text-2xl font-black text-slate-900 font-heading mt-1">{{ $subjects->count() }} Subjects</div>
                    </div>
                </div>
            </div>

            <!-- CTA Sidebar -->
            <div class="bg-slate-900 text-white rounded-3xl p-8 shadow-xl space-y-6">
                <h3 class="text-xl font-bold font-heading">Start Practicing</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Launch a fully customized exam session now. Configure time limit, question count, and select your subjects.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('exam.setup') }}" class="block w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-2xl text-center shadow-lg shadow-emerald-500/20 transition-all">
                        Launch Exam Runner
                    </a>
                    <a href="{{ route('pricing') }}" class="block w-full py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl text-center transition-colors">
                        View Upgrade Plans
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subject Syllabus Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-black text-slate-900 font-heading mb-8">Available Subjects</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($subjects as $subject)
                <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100 hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg mb-4">
                            {{ $subject->icon ?? '📚' }}
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 font-heading mb-2">
                            {{ $subject->name }}
                        </h4>
                        <p class="text-slate-500 text-xs font-semibold uppercase mb-4">
                            {{ number_format($subject->questions_count) }} Practice Questions
                        </p>
                    </div>
                    <a href="{{ route('subjects.show', $subject->slug) }}" class="inline-flex items-center gap-1 text-sm font-bold text-emerald-600 hover:text-emerald-700">
                        View Syllabus Details &rarr;
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500">No subjects currently available for this exam.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
