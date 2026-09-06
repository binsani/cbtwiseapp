@extends('layouts.public')

@section('title', 'All Subjects Syllabus — CBTWise')
@section('meta_description', 'Explore our complete library of subjects for JAMB, WAEC, and NECO exams. View topic guides and practice questions.')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "CBTWise Subject Catalog",
  "description": "Syllabus catalog for UTME, WAEC, and NECO subjects."
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
                        <span class="ml-1 text-slate-400 md:ml-2">Subjects</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="max-w-3xl mb-12">
            <h1 class="text-4xl font-black text-slate-950 font-heading tracking-tight leading-none">
                Exam <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Subjects</span>
            </h1>
            <p class="mt-4 text-slate-600">
                Explore individual subject syllabus guides, read outline topics, view sample questions, and practice selectively.
            </p>
        </div>

        @forelse($subjects as $examName => $examSubjects)
            <div class="mb-16">
                <h2 class="text-2xl font-black text-slate-900 font-heading mb-6 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-emerald-600 rounded"></span>
                    {{ $examName }} Subjects
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($examSubjects as $subject)
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg mb-4">
                                    {{ $subject->icon ?? '📚' }}
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading mb-1">
                                    {{ $subject->name }}
                                </h3>
                                <p class="text-slate-500 text-xs font-semibold uppercase mb-4">
                                    {{ number_format($subject->questions_count) }} Questions
                                </p>
                            </div>
                            <a href="{{ route('subjects.show', $subject->slug) }}" class="inline-flex items-center gap-1 text-sm font-bold text-emerald-600 hover:text-emerald-700">
                                Explore Syllabus &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-slate-500">No subjects currently available. Please seed or check database.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
