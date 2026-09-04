@extends('layouts.public')

@section('title', $page->title)
@section('meta_description', $page->meta_description)

@push('head')
{{-- JSON-LD Schema --}}
@if($page->schema_json)
<script type="application/ld+json">{!! json_encode($page->schema_json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "CBTWise",
  "url": "{{ config('app.url') }}",
  "logo": "{{ config('app.url') }}/images/logo.png"
}
</script>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50">

  {{-- Breadcrumb --}}
  <div class="bg-white border-b border-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 text-xs text-slate-500 flex items-center gap-2">
      <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">{{ __('Home') }}</a>
      <span>/</span>
      @if($page->exam)
      <a href="{{ route('exams.show', $page->exam->slug) }}" class="hover:text-emerald-600 transition-colors">{{ $page->exam->name }}</a>
      <span>/</span>
      @endif
      @if($page->subject)
      <a href="{{ route('subjects.show', $page->subject->slug) }}" class="hover:text-emerald-600 transition-colors">{{ $page->subject->name }}</a>
      <span>/</span>
      @endif
      <span class="text-slate-800 font-semibold">{{ $page->year }}</span>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      {{-- Main Content --}}
      <div class="lg:col-span-2 space-y-8">

        {{-- Hero --}}
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
          <div class="flex items-center gap-2 mb-4">
            @if($page->exam)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700">{{ $page->exam->name }}</span>
            @endif
            @if($page->year)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600">{{ $page->year }}</span>
            @endif
          </div>
          <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mb-4 leading-tight">{{ $page->h1 }}</h1>
          @if($page->body_md)
          <div class="prose prose-slate max-w-none text-slate-600 text-sm leading-relaxed">
            {!! nl2br(e($page->body_md)) !!}
          </div>
          @endif

          {{-- CTA --}}
          <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('exam.setup') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold rounded-2xl shadow-md transition-all hover:shadow-lg hover:-translate-y-0.5">
              🎯 {{ __('Practice Full Exam') }}
            </a>
            @guest
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-2xl transition-all">
              {{ __('Sign Up Free') }} →
            </a>
            @endguest
          </div>
        </div>

        {{-- Sample Questions --}}
        @if($questions->count())
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
          <h2 class="text-xl font-black text-slate-900 mb-6">
            {{ __('Sample Questions from') }} {{ $page->exam?->name }} {{ $page->subject?->name }} {{ $page->year }}
          </h2>

          <div class="space-y-4" x-data="{ open: null }">
            @foreach($questions as $i => $q)
            <div class="border border-slate-100 rounded-2xl overflow-hidden">
              <button
                @click="open = open === {{ $i }} ? null : {{ $i }}"
                class="w-full text-left px-5 py-4 flex items-start justify-between gap-4 hover:bg-slate-50 transition-colors"
              >
                <span class="text-sm font-semibold text-slate-800 leading-relaxed">
                  <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black mr-2 flex-shrink-0">{{ $i + 1 }}</span>
                  {!! $q->question_text !!}
                </span>
                <svg x-bind:class="open === {{ $i }} ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div x-show="open === {{ $i }}" x-transition class="px-5 pb-5 space-y-2">
                @foreach($q->getOptions() as $key => $val)
                <div class="flex items-start gap-3 px-4 py-2.5 rounded-xl text-sm
                  {{ strtolower($q->correct_option) === $key ? 'bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold' : 'bg-slate-50 text-slate-600' }}">
                  <span class="font-black uppercase">{{ $key }}.</span>
                  <span>{{ $val }}</span>
                  @if(strtolower($q->correct_option) === $key)
                  <span class="ml-auto text-emerald-600 text-xs font-black">✓ Correct</span>
                  @endif
                </div>
                @endforeach
                @if($q->explanation)
                <div class="mt-3 p-4 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-800">
                  <span class="font-black block mb-1">💡 Explanation</span>
                  {!! $q->explanation !!}
                </div>
                @endif
              </div>
            </div>
            @endforeach
          </div>

          <div class="mt-6 text-center">
            <a href="{{ route('exam.setup') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl shadow-md transition-all hover:shadow-lg text-sm">
              {{ __('Practice All Questions') }} →
            </a>
          </div>
        </div>
        @endif

        {{-- FAQ Schema block --}}
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
          <h2 class="text-xl font-black text-slate-900 mb-6">{{ __('Frequently Asked Questions') }}</h2>
          <div class="space-y-3" x-data="{ open: null }">
            @php
            $faqs = [
              ['q' => 'How many ' . ($page->exam?->name) . ' ' . ($page->subject?->name) . ' ' . $page->year . ' questions are available?',
               'a' => 'CBTWise has a comprehensive collection of ' . ($page->exam?->name) . ' ' . $page->year . ' ' . ($page->subject?->name) . ' past questions. You can practise all of them in CBT format with instant feedback.'],
              ['q' => 'Are the answers and explanations provided?',
               'a' => 'Yes! Every question comes with the correct answer and a detailed step-by-step explanation to help you understand the concept.'],
              ['q' => 'Is it free to practise?',
               'a' => 'CBTWise offers free daily practice. Sign up for a free account to get started, or upgrade to Premium for unlimited access.'],
              ['q' => 'Can I simulate a real ' . ($page->exam?->name) . ' exam experience?',
               'a' => 'Absolutely. CBTWise features a full timed CBT mode that mirrors the actual ' . ($page->exam?->name) . ' interface, with automatic submission when time expires.'],
            ];
            @endphp
            @foreach($faqs as $fi => $faq)
            <div class="border border-slate-100 rounded-2xl overflow-hidden">
              <button @click="open = open === {{ $fi }} ? null : {{ $fi }}" class="w-full text-left px-5 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                <span class="text-sm font-bold text-slate-800">{{ $faq['q'] }}</span>
                <svg x-bind:class="open === {{ $fi }} ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div x-show="open === {{ $fi }}" x-transition class="px-5 pb-4 text-sm text-slate-600 leading-relaxed">
                {{ $faq['a'] }}
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>

      {{-- Sidebar --}}
      <div class="space-y-6">

        {{-- Quick Start CTA --}}
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-3xl p-6 text-white shadow-lg">
          <div class="text-2xl mb-2">🎯</div>
          <h3 class="font-black text-lg mb-2">{{ __('Ready to Practice?') }}</h3>
          <p class="text-emerald-100 text-sm mb-4">{{ __('Join thousands of Nigerian students practising smarter with CBTWise.') }}</p>
          <a href="{{ route('exam.setup') }}" class="block text-center px-4 py-3 bg-white text-emerald-700 font-extrabold rounded-xl text-sm hover:bg-emerald-50 transition-colors">
            {{ __('Start Free Practice') }}
          </a>
        </div>

        {{-- Related Subjects --}}
        @if($relatedSubjects->count())
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
          <h3 class="font-black text-slate-900 mb-4 text-sm">{{ __('Related Subjects') }} ({{ $page->year }})</h3>
          <div class="space-y-2">
            @foreach($relatedSubjects as $rs)
            @if($page->exam)
            <a href="{{ url('/' . $page->exam->slug . '/' . $rs->slug . '/' . $page->year) }}"
               class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 text-sm font-semibold transition-colors">
              {{ $rs->name }}
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
            @endforeach
          </div>
        </div>
        @endif

        {{-- Stats widget --}}
        <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-sm">
          <h3 class="font-black text-sm mb-4 text-slate-300">{{ __('CBTWise at a Glance') }}</h3>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-xs text-slate-400">{{ __('Registered Students') }}</span>
              <span class="font-black text-emerald-400">50,000+</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-slate-400">{{ __('Exams Taken') }}</span>
              <span class="font-black text-emerald-400">500,000+</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-slate-400">{{ __('Questions') }}</span>
              <span class="font-black text-emerald-400">100,000+</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Bottom CTA Banner --}}
  <div class="bg-slate-900 py-14 mt-10">
    <div class="max-w-3xl mx-auto px-4 text-center">
      <h2 class="text-3xl font-black text-white mb-3">{{ __('Start Practising Now — It\'s Free') }}</h2>
      <p class="text-slate-400 mb-6">{{ __('Join over 50,000 Nigerian students who trust CBTWise for JAMB, WAEC, and NECO preparation.') }}</p>
      <div class="flex flex-wrap justify-center gap-4">
        @guest
        <a href="{{ route('register') }}" class="px-8 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-extrabold rounded-2xl shadow-lg transition-all hover:shadow-emerald-500/25 hover:-translate-y-0.5">
          {{ __('Create Free Account') }}
        </a>
        @else
        <a href="{{ route('exam.setup') }}" class="px-8 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-extrabold rounded-2xl shadow-lg transition-all hover:shadow-emerald-500/25 hover:-translate-y-0.5">
          {{ __('Start Practice Exam') }}
        </a>
        @endguest
      </div>
    </div>
  </div>

</div>
@endsection
