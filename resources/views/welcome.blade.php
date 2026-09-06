<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CBTWise — Practice Past Questions. Crush Your Exams.</title>
        <link rel="icon" type="image/png" href="/favicon.png">
        <meta name="description" content="Prepare for JAMB UTME, WAEC, and NECO with our realistic computer-based test engines, AI tutoring, and personalized analytics.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "CBTWise",
          "url": "{{ url('/') }}",
          "description": "Premium CBT practice exam platform with AI tutoring for JAMB, WAEC, and NECO."
        }
        </script>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50">
        <!-- Premium Navigation Header -->
        <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/70 border-b border-slate-100/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="/logo.png" alt="CBTWise Logo" class="h-10 w-auto rounded-xl">
                    <span class="text-2xl font-black tracking-tight font-heading bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">
                        CBTWise
                    </span>
                </div>
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="/" class="hover:text-emerald-600 transition-colors">Home</a>
                    <a href="{{ route('pricing') }}" class="hover:text-emerald-600 transition-colors">Pricing</a>
                    <a href="#about" class="hover:text-emerald-600 transition-colors">About</a>
                    <a href="#faq" class="hover:text-emerald-600 transition-colors">FAQ</a>
                    <a href="#contact" class="hover:text-emerald-600 transition-colors">Contact</a>
                </nav>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-extrabold rounded-2xl transition-all shadow-md">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors hidden sm:block">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold rounded-2xl transition-all shadow-md shadow-emerald-600/10">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-20 pb-16 lg:pt-28 lg:pb-24 bg-gradient-to-b from-emerald-50/30 via-white to-slate-50 text-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-4xl mx-auto">
                    <!-- Dynamic Badge -->
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-widest mb-6">
                        🌟 The Best Exam Prep Platform
                    </span>

                    <h1 class="text-5xl sm:text-7xl font-black text-slate-950 font-heading tracking-tight leading-none">
                        Practice Past Questions.<br />
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Crush Your Exams.</span>
                    </h1>
                    
                    <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                        Prepare for JAMB (UTME), WAEC, and NECO with thousands of past questions, real CBT simulators, and instant performance feedback — all on your phone.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl text-center shadow-xl shadow-emerald-600/20 transition-all transform hover:-translate-y-0.5 text-lg">
                            Practice Free Exams &rarr;
                        </a>
                    </div>

                    <div class="mt-10 flex flex-wrap justify-center items-center gap-6 text-sm font-semibold text-slate-600">
                        <div class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> 100,000+ Questions</div>
                        <div class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Timed CBT Mode</div>
                        <div class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Performance Analytics</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Choose Your Exam Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 font-heading tracking-tight">
                        Choose Your Exam
                    </h2>
                    <p class="mt-4 text-slate-600">We have extensively curated standard materials across Nigerian academic tiers.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- UTME -->
                    <div class="bg-white rounded-3xl p-8 border border-emerald-100 shadow-sm hover:shadow-xl transition-all">
                        <div class="w-12 h-1 bg-emerald-500 mb-6 rounded-full"></div>
                        <h3 class="text-2xl font-black text-slate-900 font-heading mb-4">UTME (JAMB)</h3>
                        <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                            Complete access to UTME standard subjects with AI explanations and accurate time limits.
                        </p>
                        <p class="text-xs text-slate-500 mb-8 font-semibold">
                            Subjects: English, Mathematics, Physics, Chemistry, Biology, Economics & more.
                        </p>
                        <a href="{{ route('register') }}" class="block w-full py-3 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-extrabold rounded-xl text-center transition-colors">
                            Practice Now &rarr;
                        </a>
                    </div>

                    <!-- WAEC -->
                    <div class="bg-white rounded-3xl p-8 border border-emerald-100 shadow-sm hover:shadow-xl transition-all">
                        <div class="w-12 h-1 bg-emerald-500 mb-6 rounded-full"></div>
                        <h3 class="text-2xl font-black text-slate-900 font-heading mb-4">WAEC</h3>
                        <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                            Get familiar with SSCE pattern objective questions and improve your chances of getting A's.
                        </p>
                        <p class="text-xs text-slate-500 mb-8 font-semibold">
                            Subjects: English, Mathematics, Physics, Chemistry, Biology, Economics & more.
                        </p>
                        <a href="{{ route('register') }}" class="block w-full py-3 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-extrabold rounded-xl text-center transition-colors">
                            Practice Now &rarr;
                        </a>
                    </div>

                    <!-- NECO -->
                    <div class="bg-white rounded-3xl p-8 border border-emerald-100 shadow-sm hover:shadow-xl transition-all">
                        <div class="w-12 h-1 bg-emerald-500 mb-6 rounded-full"></div>
                        <h3 class="text-2xl font-black text-slate-900 font-heading mb-4">NECO</h3>
                        <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                            Master NECO standard objective exams quickly. Validate your knowledge safely before the D-day.
                        </p>
                        <p class="text-xs text-slate-500 mb-8 font-semibold">
                            Subjects: English, Mathematics, Physics, Chemistry, Biology, Economics & more.
                        </p>
                        <a href="{{ route('register') }}" class="block w-full py-3 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-extrabold rounded-xl text-center transition-colors">
                            Practice Now &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 font-heading tracking-tight">
                        How It Works
                    </h2>
                    <p class="mt-4 text-slate-600">Three simple steps to start crushing your exams.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <!-- Connector Line -->
                    <div class="hidden md:block absolute top-12 left-1/6 right-1/6 h-0.5 bg-emerald-200 z-0"></div>
                    
                    <div class="relative z-10 text-center">
                        <div class="w-24 h-24 mx-auto bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mb-6 shadow-sm border-4 border-white">
                            <span class="text-3xl font-black">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Pick Your Exam</h3>
                        <p class="text-slate-600 text-sm">Select between JAMB, WAEC, or NECO and pick the specific subjects you are preparing for.</p>
                    </div>

                    <div class="relative z-10 text-center">
                        <div class="w-24 h-24 mx-auto bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mb-6 shadow-sm border-4 border-white">
                            <span class="text-3xl font-black">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Practice in Real Modes</h3>
                        <p class="text-slate-600 text-sm">Experience our CBT environment with exactly the same interface as the real test.</p>
                    </div>

                    <div class="relative z-10 text-center">
                        <div class="w-24 h-24 mx-auto bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mb-6 shadow-sm border-4 border-white">
                            <span class="text-3xl font-black">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Get Results & Improve</h3>
                        <p class="text-slate-600 text-sm">Review your performance, get AI-powered explanations, and work on your weak spots.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Students Love CBTWise -->
        <section id="features" class="py-20 bg-white border-t border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 font-heading tracking-tight">
                        Why Students Love CBTWise
                    </h2>
                    <p class="mt-4 text-slate-600">Everything you need to perform optimally on your exam.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-slate-50/50 rounded-3xl p-8 border border-slate-100 hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading">Real CBT Experience</h3>
                        <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                            Practice in our CBT environment mirroring the exact interface you'll face on exam day.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-slate-50/50 rounded-3xl p-8 border border-slate-100 hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading">Detailed Explanations</h3>
                        <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                            Every question comes with step-by-step reasoning so you learn exactly where you went wrong.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-slate-50/50 rounded-3xl p-8 border border-slate-100 hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading">Performance Tracking</h3>
                        <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                            Track your scores over time and automatically identify weak topics for targeted revision.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-slate-50/50 rounded-3xl p-8 border border-slate-100 hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading">Mobile-First Design</h3>
                        <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                            Study anywhere - our platform is fully optimized for reading, testing, and analytics on mobile.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="bg-slate-50/50 rounded-3xl p-8 border border-slate-100 hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading">Timed Practice</h3>
                        <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                            Build exam speed and accuracy with real counting timers and automatic submission.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="bg-slate-50/50 rounded-3xl p-8 border border-slate-100 hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading">Trusted Platform</h3>
                        <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                            Join thousands of successful students who passed their exams with flying colors.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 font-heading tracking-tight">
                        What Students Are Saying
                    </h2>
                    <p class="mt-4 text-slate-600">Hear real success stories from our users.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                        <div class="flex text-yellow-400 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-slate-600 text-sm italic mb-6 leading-relaxed">
                            "CBTWise helped me so much with my JAMB. The explanations for every question saved me a lot of time!"
                        </p>
                        <h4 class="font-bold text-slate-900 text-sm">Chioma A.</h4>
                        <p class="text-xs text-slate-500">JAMB 2024: Scored 315</p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                        <div class="flex text-yellow-400 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-slate-600 text-sm italic mb-6 leading-relaxed">
                            "The platform is very user friendly. I loved tracking my progress day by day until my exam date."
                        </p>
                        <h4 class="font-bold text-slate-900 text-sm">Daniel O.</h4>
                        <p class="text-xs text-slate-500">WAEC 2024: 7 A1s, 2 B2s</p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                        <div class="flex text-yellow-400 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-slate-600 text-sm italic mb-6 leading-relaxed">
                            "Thanks CBTWise for the platform, especially the Analytics page. The CBT mode made me a lot more confident."
                        </p>
                        <h4 class="font-bold text-slate-900 text-sm">Fatima B.</h4>
                        <p class="text-xs text-slate-500">JAMB 2024: Scored 298</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 font-heading tracking-tight">
                        Simple, Student-Friendly Pricing
                    </h2>
                    <p class="mt-4 text-slate-600">Start free. Upgrade when you need more power.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <!-- Free Plan -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between hover:border-emerald-200 transition-all">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 font-heading">Free</h3>
                            <div class="mt-4 flex items-baseline">
                                <span class="text-4xl font-black text-slate-900 font-heading">&#8358;0</span>
                                <span class="text-slate-500 text-sm ml-2">/ forever</span>
                            </div>
                            <ul class="mt-8 space-y-4 text-slate-600 text-sm font-medium">
                                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 50 Questions per Exam</li>
                                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Basic Analytics & History</li>
                                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 1 Subject at a time</li>
                                <li class="flex items-center gap-3 text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> AI Tutor Coach</li>
                            </ul>
                        </div>
                        <a href="{{ route('register') }}" class="mt-10 block w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-2xl text-center transition-colors">
                            Get Started
                        </a>
                    </div>

                    <!-- Premium Plan -->
                    <div class="bg-white rounded-3xl p-8 border-2 border-emerald-500 shadow-xl shadow-emerald-500/10 flex flex-col justify-between relative overflow-hidden">
                        <span class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-400"></span>
                        <div>
                            <h3 class="text-xl font-bold text-emerald-600 font-heading">Premium</h3>
                            <div class="mt-4 flex items-baseline">
                                <span class="text-4xl font-black text-slate-900 font-heading">&#8358;1,500</span>
                                <span class="text-slate-500 text-sm ml-2">/ month</span>
                            </div>
                            <ul class="mt-8 space-y-4 text-slate-600 text-sm font-medium">
                                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Full Exam Simulation</li>
                                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Advanced Analytics Metrics</li>
                                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 4 Subjects concurrently</li>
                                <li class="flex items-center gap-3 font-bold text-emerald-600"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Access to AI Tutor Explanations</li>
                            </ul>
                        </div>
                        <a href="{{ route('pricing') }}" class="mt-10 block w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-center transition-colors shadow-lg shadow-emerald-600/20">
                            Go Premium
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQs Section -->
        <section id="faq" class="py-20 bg-slate-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 font-heading tracking-tight">
                        Frequently Asked Questions
                    </h2>
                </div>

                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <details class="group bg-white rounded-2xl border border-slate-200 [&_summary::-webkit-details-marker]:hidden">
                        <summary class="flex cursor-pointer items-center justify-between gap-1.5 p-6 text-slate-900 font-bold">
                            Is CBTWise free to use?
                            <span class="relative h-5 w-5 shrink-0 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-100 group-open:opacity-0 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-0 group-open:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                            Yes! We have a completely free tier that allows you to take practice tests with a limited set of questions. For the full experience including AI explanations, you can upgrade to Premium.
                        </div>
                    </details>
                    
                    <!-- FAQ 2 -->
                    <details class="group bg-white rounded-2xl border border-slate-200 [&_summary::-webkit-details-marker]:hidden">
                        <summary class="flex cursor-pointer items-center justify-between gap-1.5 p-6 text-slate-900 font-bold">
                            What exams do you currently cover?
                            <span class="relative h-5 w-5 shrink-0 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-100 group-open:opacity-0 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-0 group-open:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                            We currently cover major Nigerian exams including JAMB (UTME), WAEC (SSCE), and NECO. We are constantly updating our question banks.
                        </div>
                    </details>

                    <!-- FAQ 3 -->
                    <details class="group bg-white rounded-2xl border border-slate-200 [&_summary::-webkit-details-marker]:hidden">
                        <summary class="flex cursor-pointer items-center justify-between gap-1.5 p-6 text-slate-900 font-bold">
                            Are the questions updated?
                            <span class="relative h-5 w-5 shrink-0 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-100 group-open:opacity-0 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-0 group-open:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                            Absolutely. We rigorously collect and verify past questions up to the most recent years to ensure you practice with relevant materials.
                        </div>
                    </details>

                    <!-- FAQ 4 -->
                    <details class="group bg-white rounded-2xl border border-slate-200 [&_summary::-webkit-details-marker]:hidden">
                        <summary class="flex cursor-pointer items-center justify-between gap-1.5 p-6 text-slate-900 font-bold">
                            Can I use CBTWise on my phone?
                            <span class="relative h-5 w-5 shrink-0 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-100 group-open:opacity-0 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 h-5 w-5 opacity-0 group-open:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                            Yes! Our platform is 100% mobile responsive. You can take tests comfortably on any device – smartphone, tablet, or PC.
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <!-- Bottom CTA Section -->
        <section class="py-20 bg-white">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-emerald-700 to-teal-800 rounded-[2rem] p-10 md:p-16 text-center shadow-2xl relative overflow-hidden">
                    <!-- Decor elements -->
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-5 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-emerald-400 opacity-20 blur-3xl"></div>
                    
                    <h2 class="text-3xl sm:text-5xl font-black text-white font-heading tracking-tight relative z-10">
                        Ready to Start Drilling?
                    </h2>
                    <p class="mt-4 text-emerald-100 text-lg sm:text-xl max-w-2xl mx-auto relative z-10">
                        Join thousands of students already preparing smart with CBTWise.
                    </p>
                    <div class="mt-10 relative z-10">
                        <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-black rounded-2xl text-center shadow-xl transition-all transform hover:-translate-y-0.5 text-lg uppercase tracking-wide">
                            Create Free Account &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Extended Multi-column Footer -->
        <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                    <!-- Brand -->
                    <div class="col-span-1 md:col-span-1">
                        <div class="flex items-center gap-3 mb-6">
                            <img src="/logo.png" alt="CBTWise Logo" class="h-8 w-auto rounded-lg">
                            <span class="text-xl font-black tracking-tight font-heading text-slate-900">
                                CBTWise
                            </span>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">
                            Nigeria's premium CBT practice platform for JAMB (UTME), WAEC, and NECO exams. Prepare smarter.
                        </p>
                    </div>

                    <!-- Exams Links -->
                    <div>
                        <h4 class="font-bold text-slate-900 mb-6">Exams</h4>
                        <ul class="space-y-4 text-sm text-slate-500">
                            <li><a href="{{ route('register') }}" class="hover:text-emerald-600 transition-colors">JAMB (UTME)</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-emerald-600 transition-colors">WAEC</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-emerald-600 transition-colors">NECO</a></li>
                        </ul>
                    </div>

                    <!-- Company Links -->
                    <div>
                        <h4 class="font-bold text-slate-900 mb-6">Company</h4>
                        <ul class="space-y-4 text-sm text-slate-500">
                            <li><a href="#about" class="hover:text-emerald-600 transition-colors">About Us</a></li>
                            <li><a href="{{ route('pricing') }}" class="hover:text-emerald-600 transition-colors">Pricing</a></li>
                            <li><a href="{{ route('blog.index') }}" class="hover:text-emerald-600 transition-colors">Blog</a></li>
                            <li><a href="#contact" class="hover:text-emerald-600 transition-colors">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Legal Links -->
                    <div>
                        <h4 class="font-bold text-slate-900 mb-6">Legal</h4>
                        <ul class="space-y-4 text-sm text-slate-500">
                            <li><a href="{{ route('terms') }}" class="hover:text-emerald-600 transition-colors">Terms of Service</a></li>
                            <li><a href="{{ route('privacy') }}" class="hover:text-emerald-600 transition-colors">Privacy Policy</a></li>
                            <li><a href="{{ route('refund-policy') }}" class="hover:text-emerald-600 transition-colors">Refund Policy</a></li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-500 text-sm">
                        &copy; {{ date('Y') }} CBTWise. All rights reserved.
                    </p>
                    <div class="flex gap-4">
                        <!-- Social Icons (Placeholders) -->
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
