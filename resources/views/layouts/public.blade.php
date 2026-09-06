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
        <!-- Navigation Header -->
        <header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 backdrop-blur-xl bg-white/90 border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="/logo.png" alt="CBTWise Logo" class="h-9 w-auto">
                    <span class="text-2xl font-bold tracking-tight text-slate-900 font-heading">
                        CBT<span class="text-emerald-600">Wise</span>
                    </span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors {{ request()->is('/') ? 'text-emerald-600 font-semibold' : '' }}">Home</a>
                    <a href="{{ route('pricing') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('pricing') ? 'text-emerald-600 font-semibold' : '' }}">Pricing</a>
                    <a href="{{ route('about') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('about') ? 'text-emerald-600 font-semibold' : '' }}">About</a>
                    <a href="{{ route('faq') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('faq') ? 'text-emerald-600 font-semibold' : '' }}">FAQ</a>
                    <a href="{{ route('contact') }}" class="hover:text-emerald-600 transition-colors {{ request()->routeIs('contact') ? 'text-emerald-600 font-semibold' : '' }}">Contact</a>
                </nav>

                <!-- Desktop CTA -->
                <div class="hidden sm:flex items-center gap-5">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-full transition-all shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-700 hover:text-emerald-600 transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-[#05603A] hover:bg-[#044c2e] text-white text-sm font-semibold rounded-full transition-all shadow-sm">
                            Get Started
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileOpen = ! mobileOpen" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle Menu">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileOpen, 'inline-flex': ! mobileOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileOpen" x-cloak class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-2">
                <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600">Home</a>
                <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600">Pricing</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600">About</a>
                <a href="{{ route('faq') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600">FAQ</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-emerald-600">Contact</a>
                <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full text-center px-4 py-2.5 bg-slate-900 text-white font-semibold rounded-full">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center px-4 py-2 text-slate-700 font-medium">Log in</a>
                        <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 bg-[#05603A] text-white font-semibold rounded-full">Get Started</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-16 text-left">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2.5">
                            <img src="/logo.png" alt="CBTWise Logo" class="h-8 w-auto">
                            <span class="text-xl font-bold tracking-tight text-slate-900 font-heading">
                                CBT<span class="text-emerald-600">Wise</span>
                            </span>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-xs">
                            Nigeria's trusted CBT practice platform for UTME, WAEC, and NECO exam preparation.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-4">Exams</h4>
                        <ul class="space-y-2.5 text-sm text-slate-600">
                            <li><a href="{{ route('exams.show', 'utme') }}" class="hover:text-emerald-600 transition-colors">UTME (JAMB)</a></li>
                            <li><a href="{{ route('exams.show', 'waec') }}" class="hover:text-emerald-600 transition-colors">WAEC</a></li>
                            <li><a href="{{ route('exams.show', 'neco') }}" class="hover:text-emerald-600 transition-colors">NECO</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-4">Company</h4>
                        <ul class="space-y-2.5 text-sm text-slate-600">
                            <li><a href="{{ route('about') }}" class="hover:text-emerald-600 transition-colors">About</a></li>
                            <li><a href="{{ route('pricing') }}" class="hover:text-emerald-600 transition-colors">Pricing</a></li>
                            <li><a href="{{ route('faq') }}" class="hover:text-emerald-600 transition-colors">FAQ</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-emerald-600 transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-4">Legal</h4>
                        <ul class="space-y-2.5 text-sm text-slate-600">
                            <li><a href="{{ route('terms') }}" class="hover:text-emerald-600 transition-colors">Terms of Service</a></li>
                            <li><a href="{{ route('privacy') }}" class="hover:text-emerald-600 transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-8 text-center text-slate-400 text-sm">
                    <p>&copy; 2026 CBTWise. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
