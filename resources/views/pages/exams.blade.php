@extends('layouts.public')

@section('title', 'Select Exam — CBTWise Practice Simulator')
@section('meta_description', 'Choose from our comprehensive library of simulated computer-based tests for JAMB UTME, WAEC, and NECO exams.')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "ItemList",
  "name": "CBTWise Exams Library",
  "itemListElement": [
    @foreach($exams as $index => $exam)
    {
      "@@type": "ListItem",
      "position": {{ $index + 1 }},
      "url": "{{ route('exams.show', $exam->slug) }}",
      "name": "{{ $exam->name }}"
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endsection

@section('content')
<section class="py-20 bg-gradient-to-b from-emerald-50/20 via-white to-slate-50">
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
                        <span class="ml-1 text-slate-400 md:ml-2">Exams</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="max-w-3xl mb-12">
            <h1 class="text-4xl font-black text-slate-950 font-heading tracking-tight leading-none">
                Supported <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Exams</span>
            </h1>
            <p class="mt-4 text-slate-600">
                Select your upcoming exam to explore the syllabus, view subject layouts, and start preparing with detailed past questions.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($exams as $exam)
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-wider rounded-lg border border-emerald-100">
                                Official Syllabus
                            </span>
                            <span class="text-sm text-slate-400 font-medium">
                                {{ number_format($exam->questions_count) }} Questions
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 font-heading mb-3">
                            {{ $exam->name }}
                        </h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">
                            {{ $exam->description ?? 'Practice realistic full-length and topic-wise CBT prep tests aligned directly with official curriculum standards.' }}
                        </p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('exams.show', $exam->slug) }}" class="block w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold rounded-2xl text-center text-sm shadow-sm transition-colors">
                            Explore Syllabus
                        </a>
                        <a href="{{ route('exam.setup') }}" class="block w-full py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-2xl text-center text-sm transition-colors">
                            Start Practice
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500">No exams registered yet. Please seed or check back later.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
