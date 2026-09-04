@extends('layouts.public')

@section('title', 'About CBTWise — Premium Practice Exam Platform')
@section('meta_description', 'Discover how CBTWise is democratizing exam preparation for UTME, WAEC, and NECO students in Nigeria using state-of-the-art simulator engines.')

@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "About CBTWise",
  "description": "Democratizing exam preparation for UTME, WAEC, and NECO students in Nigeria.",
  "publisher": {
    "@type": "EducationalOrganization",
    "name": "CBTWise",
    "url": "{{ url('/') }}",
    "logo": "{{ url('/logo.png') }}"
  }
}
</script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="relative py-20 bg-gradient-to-b from-emerald-50/30 via-white to-slate-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Breadcrumb -->
        <nav class="flex justify-center mb-6 text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-slate-400 md:ml-2">About</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-4xl sm:text-5xl font-black text-slate-950 font-heading tracking-tight leading-none">
            About <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">CBTWise</span>
        </h1>
        <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
            We are building Nigeria's most advanced learning and testing platform to help millions of secondary and tertiary students ace their exams with confidence.
        </p>
    </div>
</section>

<!-- Stats Bar -->
<section class="py-10 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-4xl font-extrabold text-emerald-400 font-heading">50,000+</div>
                <div class="mt-2 text-slate-400 text-sm font-semibold">Active Students</div>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-emerald-400 font-heading">100,000+</div>
                <div class="mt-2 text-slate-400 text-sm font-semibold">CBT Questions</div>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-emerald-400 font-heading">99.2%</div>
                <div class="mt-2 text-slate-400 text-sm font-semibold">Satisfaction Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Approach Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-lg transition-all">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6 text-xl font-bold">🎯</div>
                <h3 class="text-xl font-bold text-slate-900 font-heading">Our Mission</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    To democratize access to premium exam preparation materials, allowing every Nigerian student, regardless of background, to reach their full potential.
                </p>
            </div>
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-lg transition-all">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6 text-xl font-bold">🇳🇬</div>
                <h3 class="text-xl font-bold text-slate-900 font-heading">Why Nigeria</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    National exams like JAMB and WAEC determine the futures of millions of young minds. We bridge the digital divide by offering a state-of-the-art simulator that replicates real conditions.
                </p>
            </div>
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-lg transition-all">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6 text-xl font-bold">🧠</div>
                <h3 class="text-xl font-bold text-slate-900 font-heading">Our Approach</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    We combine standard past question drills with cutting-edge AI tutoring and personalized analytics, transforming rote memorization into real logical mastery.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nigerian Exams Coverage Badge Area -->
<section class="py-16 bg-slate-50 border-t border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-black text-slate-900 font-heading mb-8">Aligned with Official Curriculum & Bodies</h2>
        <div class="flex flex-wrap justify-center gap-6">
            <span class="px-6 py-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm text-sm font-bold text-slate-700">JAMB UTME</span>
            <span class="px-6 py-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm text-sm font-bold text-slate-700">WAEC SSCE</span>
            <span class="px-6 py-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm text-sm font-bold text-slate-700">NECO SSCE</span>
            <span class="px-6 py-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm text-sm font-bold text-slate-700">POST-UTME</span>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl font-black text-slate-900 font-heading">Meet the Founders</h2>
            <p class="mt-3 text-slate-600">The passionate team behind Nigeria's leading digital tutor platform.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="text-center bg-slate-50 p-6 rounded-3xl border border-slate-100">
                <div class="w-24 h-24 rounded-full bg-emerald-100 text-slate-900 flex items-center justify-center text-3xl mx-auto mb-4 font-bold">👨‍💻</div>
                <h4 class="text-lg font-bold text-slate-900 font-heading">Chidi Egwu</h4>
                <p class="text-emerald-600 text-sm font-semibold">Co-founder & CEO</p>
                <p class="mt-2 text-slate-500 text-xs">Ex-Edtech Lead at AfricaVarsity. Passionate about scalable learning infrastructures.</p>
            </div>
            <div class="text-center bg-slate-50 p-6 rounded-3xl border border-slate-100">
                <div class="w-24 h-24 rounded-full bg-emerald-100 text-slate-900 flex items-center justify-center text-3xl mx-auto mb-4 font-bold">👩‍💻</div>
                <h4 class="text-lg font-bold text-slate-900 font-heading">Tolu Awosika</h4>
                <p class="text-emerald-600 text-sm font-semibold">Co-founder & CPO</p>
                <p class="mt-2 text-slate-500 text-xs">Product Designer. Focused on student ergonomics and micro-learning workflows.</p>
            </div>
            <div class="text-center bg-slate-50 p-6 rounded-3xl border border-slate-100">
                <div class="w-24 h-24 rounded-full bg-emerald-100 text-slate-900 flex items-center justify-center text-3xl mx-auto mb-4 font-bold">👨‍🔬</div>
                <h4 class="text-lg font-bold text-slate-900 font-heading">Dr. Sani Bello</h4>
                <p class="text-emerald-600 text-sm font-semibold">Advisor & Academic Lead</p>
                <p class="mt-2 text-slate-500 text-xs">Professor of Curricular Studies. Directs our past-question validation accuracy.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-r from-emerald-600 to-teal-500 text-white text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-extrabold font-heading">Ready to Ace Your Exams?</h2>
        <p class="mt-4 text-emerald-50 max-w-xl mx-auto text-base">
            Get instant access to thousands of syllabus-aligned practice questions and full-length simulated examinations.
        </p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-slate-950 hover:bg-slate-900 text-white font-extrabold rounded-2xl shadow-lg transition-transform hover:-translate-y-0.5">
                Create Free Account
            </a>
        </div>
    </div>
</section>
@endsection
