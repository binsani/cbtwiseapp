<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'CBTWise — Nigeria\'s Premium Exam CBT Simulator')</title>
        <link rel="icon" type="image/png" href="/favicon.png">
        <meta name="description" content="@yield('meta_description', 'Prepare for JAMB UTME, WAEC, and NECO with our realistic computer-based test engines, AI tutoring, and personalized analytics.')">
        <link rel="canonical" href="@yield('canonical', request()->url())" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="@yield('canonical', request()->url())">
        <meta property="og:title" content="@yield('og_title', 'CBTWise — Nigeria\'s Premium Exam CBT Simulator')">
        <meta property="og:description" content="@yield('og_description', 'Prepare for JAMB UTME, WAEC, and NECO with our realistic computer-based test engines, AI tutoring, and personalized analytics.')">
        <meta property="og:image" content="@yield('og_image', url('/logo.png'))">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="@yield('canonical', request()->url())">
        <meta property="twitter:title" content="@yield('og_title', 'CBTWise — Nigeria\'s Premium Exam CBT Simulator')">
        <meta property="twitter:description" content="@yield('og_description', 'Prepare for JAMB UTME, WAEC, and NECO with our realistic computer-based test engines, AI tutoring, and personalized analytics.')">
        <meta property="twitter:image" content="@yield('og_image', url('/logo.png'))">

        @yield('json_ld')
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50/50">
        <!-- Premium Navigation Header -->
        <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/70 border-b border-slate-100/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="/logo.png" alt="CBTWise Logo" class="h-10 w-auto rounded-xl">
                    <span class="text-2xl font-black tracking-tight font-heading bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">
                        CBTWise
                    </span>
                </a>
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="{{ route('exams.index') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('exams.*') ? 'text-emerald-600' : '' }}">Exams</a>
                    <a href="{{ route('subjects.index') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('subjects.*') ? 'text-emerald-600' : '' }}">Subjects</a>
                    <a href="{{ route('pricing') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('pricing') ? 'text-emerald-600' : '' }}">Pricing</a>
                    <a href="{{ route('redeem') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('redeem') ? 'text-emerald-600' : '' }}">Redeem Code</a>
                    <a href="{{ route('about') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('about') ? 'text-emerald-600' : '' }}">About</a>
                    <a href="{{ route('faq') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('faq') ? 'text-emerald-600' : '' }}">FAQ</a>
                    <a href="{{ route('contact') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('contact') ? 'text-emerald-600' : '' }}">Contact</a>
                </nav>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-extrabold rounded-2xl transition-all shadow-md">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold rounded-2xl transition-all shadow-md shadow-emerald-600/10">
                            Register Free
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 text-left">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <img src="/logo.png" alt="CBTWise Logo" class="h-8 w-auto rounded-lg">
                            <span class="text-xl font-black tracking-tight font-heading bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">
                                CBTWise
                            </span>
                        </div>
                        <p class="text-slate-500 text-sm">
                            Nigeria's premium practice exam simulator. Excel in JAMB UTME, WAEC, and NECO.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Practice</h4>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li><a href="{{ route('exams.index') }}" class="hover:text-emerald-600 transition-colors">All Exams</a></li>
                            <li><a href="{{ route('subjects.index') }}" class="hover:text-emerald-600 transition-colors">All Subjects</a></li>
                            <li><a href="{{ route('pricing') }}" class="hover:text-emerald-600 transition-colors">Pricing Plans</a></li>
                            <li><a href="{{ route('redeem') }}" class="hover:text-emerald-600 transition-colors">Redeem Voucher</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Company</h4>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li><a href="{{ route('about') }}" class="hover:text-emerald-600 transition-colors">About Us</a></li>
                            <li><a href="{{ route('faq') }}" class="hover:text-emerald-600 transition-colors">FAQ</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-emerald-600 transition-colors">Contact Support</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Legal</h4>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li><a href="{{ route('terms') }}" class="hover:text-emerald-600 transition-colors">Terms of Service</a></li>
                            <li><a href="{{ route('privacy') }}" class="hover:text-emerald-600 transition-colors">Privacy Policy</a></li>
                            <li><a href="{{ route('refund-policy') }}" class="hover:text-emerald-600 transition-colors">Refund Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-8 flex flex-col sm:flex-row justify-between items-center text-slate-500 text-sm gap-4">
                    <p>&copy; {{ date('Y') }} CBTWise. All rights reserved.</p>
                    <p>Designed for Nigerian Scholars.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
