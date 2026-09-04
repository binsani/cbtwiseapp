@extends('layouts.public')

@section('title', $subject->name . ' (' . ($subject->exam?->name ?? 'General') . ') Syllabus — CBTWise')
@section('meta_description', 'Study ' . $subject->name . ' past questions and syllabus topics. Access ' . number_format($totalQuestions) . ' practice questions from ' . ($yearRange->min_year ?? 'past') . ' to ' . ($yearRange->max_year ?? 'recent') . ' exams.')

@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "{{ $subject->name }} Syllabus",
  "description": "Comprehensive syllabus outline and past questions simulator for {{ $subject->name }} under {{ $subject->exam?->name ?? 'General' }}.",
  "provider": {
    "@type": "EducationalOrganization",
    "name": "CBTWise",
    "url": "{{ url('/') }}"
  }
}
</script>
@endsection

@section('content')
<!-- Banner -->
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
                        <a href="{{ route('subjects.index') }}" class="ml-1 hover:text-emerald-600 transition-colors md:ml-2">Subjects</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-slate-400 md:ml-2">{{ $subject->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-wider rounded border border-emerald-100">
                        {{ $subject->exam?->name ?? 'General' }}
                    </span>
                    <span class="text-sm text-slate-400 font-medium">Syllabus Guide</span>
                </div>
                <h1 class="text-4xl font-black text-slate-950 font-heading tracking-tight">
                    {{ $subject->name }}
                </h1>
                <p class="text-slate-600 text-lg leading-relaxed">
                    Prepare thoroughly with a curated library of syllabus topics and detailed past questions. Master key concepts using instant AI-guided tutorials.
                </p>

                <!-- Stats -->
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
                        <div class="text-slate-400 text-xs font-semibold uppercase">Topics Outlined</div>
                        <div class="text-2xl font-black text-slate-900 font-heading mt-1">{{ $topics->count() }} Topics</div>
                    </div>
                </div>
            </div>

            <!-- CTA Sidebar -->
            <div class="bg-slate-900 text-white rounded-3xl p-8 shadow-xl space-y-6">
                <h3 class="text-xl font-bold font-heading">Practice {{ $subject->name }}</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Start a customized practice run focused exclusively on {{ $subject->name }}. Use our study guides to structure your preparation.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('exam.setup') }}" class="block w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-2xl text-center shadow-lg shadow-emerald-500/20 transition-all">
                        Practice This Subject
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Topics list -->
            <div class="lg:col-span-2 space-y-6">
                <h3 class="text-2xl font-black text-slate-900 font-heading mb-4">Syllabus Outlines</h3>
                <div class="space-y-3">
                    @forelse($topics as $topic)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="font-bold text-slate-800 text-sm">{{ $topic->name }}</span>
                            <span class="text-xs font-semibold bg-white px-2.5 py-1 rounded-lg border border-slate-200/60 text-slate-500">
                                {{ number_format($topic->questions_count) }} Questions
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-500 text-sm">No topics have been registered for this subject yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Sample questions -->
            <div class="space-y-6">
                <h3 class="text-2xl font-black text-slate-900 font-heading mb-4">Sample Questions</h3>
                <div class="space-y-4">
                    @forelse($sampleQuestions as $question)
                        <div class="p-5 bg-white border border-slate-100 rounded-3xl shadow-sm space-y-3">
                            <div class="flex justify-between items-center text-xs font-bold text-slate-400 uppercase">
                                <span>Question #{{ $loop->iteration }}</span>
                                @if($question->year)
                                    <span>Year {{ $question->year }}</span>
                                @endif
                            </div>
                            <div class="text-slate-700 text-sm leading-relaxed font-medium">
                                {!! nl2br(e($question->question_text)) !!}
                            </div>
                            <!-- Options block -->
                            <div class="space-y-2 text-xs font-semibold text-slate-600">
                                @if($question->option_a) <div class="px-3 py-2 bg-slate-50 rounded-lg">A: {{ $question->option_a }}</div> @endif
                                @if($question->option_b) <div class="px-3 py-2 bg-slate-50 rounded-lg">B: {{ $question->option_b }}</div> @endif
                                @if($question->option_c) <div class="px-3 py-2 bg-slate-50 rounded-lg">C: {{ $question->option_c }}</div> @endif
                                @if($question->option_d) <div class="px-3 py-2 bg-slate-50 rounded-lg">D: {{ $question->option_d }}</div> @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-sm">No sample questions available for this subject.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
