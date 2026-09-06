@extends('layouts.public')

@section('title', 'About CBTWise — Practice Confidently for JAMB, WAEC & NECO')
@section('meta_description', "We're on a mission to help every Nigerian student prepare confidently for their exams — from the comfort of their phone.")

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "AboutPage",
  "name": "About CBTWise",
  "description": "We're on a mission to help every Nigerian student prepare confidently for their exams — from the comfort of their phone.",
  "publisher": {
    "@@type": "EducationalOrganization",
    "name": "CBTWise",
    "url": "{{ url('/') }}",
    "logo": "{{ url('/logo.png') }}"
  }
}
</script>
@endsection

@section('content')
<div class="bg-white py-16 sm:py-20 md:py-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-12">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight font-heading">
                About CBTWise
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl leading-relaxed">
                We're on a mission to help every Nigerian student prepare confidently for their exams &mdash; from the comfort of their phone.
            </p>
        </div>

        <!-- 4 Core Pillars Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Card 1: Our Mission -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-7 sm:p-8 hover:border-slate-300 transition-colors shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6">
                    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <circle cx="12" cy="12" r="6"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight mb-2">
                    Our Mission
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Make quality exam preparation accessible and affordable for every Nigerian student preparing for UTME, WAEC, or NECO.
                </p>
            </div>

            <!-- Card 2: What We Do -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-7 sm:p-8 hover:border-slate-300 transition-colors shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6">
                    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight mb-2">
                    What We Do
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    We provide curated past questions, realistic CBT simulations, detailed explanations, and smart performance analytics.
                </p>
            </div>

            <!-- Card 3: Who We Serve -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-7 sm:p-8 hover:border-slate-300 transition-colors shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6">
                    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight mb-2">
                    Who We Serve
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Secondary school students, JAMB candidates, and anyone preparing for WAEC or NECO examinations across Nigeria.
                </p>
            </div>

            <!-- Card 4: Why It Matters -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-7 sm:p-8 hover:border-slate-300 transition-colors shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6">
                    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight mb-2">
                    Why It Matters
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Education changes lives. We believe every student deserves the best tools to prepare, regardless of location or income.
                </p>
            </div>
        </div>

        <!-- Built in Nigeria, for Nigeria Card -->
        <div class="mt-6 bg-[#edfbf2] border border-emerald-100/90 rounded-2xl sm:rounded-3xl p-7 sm:p-9">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight mb-3">
                Built in Nigeria, for Nigeria
            </h2>
            <p class="text-sm sm:text-base text-slate-700 leading-relaxed">
                CBTWise is designed specifically for the Nigerian education system. We focus exclusively on UTME (JAMB), WAEC, and NECO &mdash; the three exams that matter most for your academic journey. Our platform is optimised for mobile usage and low data consumption, because we know that's how most Nigerian students access the internet.
            </p>
        </div>

    </div>
</div>
@endsection
