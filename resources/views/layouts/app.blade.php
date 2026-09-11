<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'CBTWise'))</title>
        <meta name="description" content="@yield('meta_description', 'Practice CBT online for JAMB UTME, WAEC, and NECO exams.')">

        <!-- PWA Support -->
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#10b981">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

        <!-- Prefetching Key Routes -->
        <link rel="prefetch" href="/dashboard">
        <link rel="prefetch" href="/exam/setup">
        <link rel="prefetch" href="/pricing">
        <link rel="prefetch" href="/redeem">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Service Worker & PWA Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(reg => console.log('Service Worker registered successfully.'))
                        .catch(err => console.error('Service Worker registration failed: ', err));
                });
            }

            // Stash install prompt for custom banner
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                window.deferredPrompt = e;
                window.dispatchEvent(new CustomEvent('pwa-installable'));
            });
        </script>

        @yield('json_ld')
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-emerald-500 selection:text-white">
        <div class="min-h-dvh bg-slate-50 overflow-x-hidden flex flex-col">
            @if(!request()->is('admin*') && !request()->routeIs('exam.run*'))
                <livewire:layout.navigation />
            @endif

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-slate-200/80">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 {{ request()->is('admin*') ? 'pb-8' : (request()->routeIs('exam.run*') ? 'pb-0' : 'pb-24 sm:pb-28 lg:pb-8') }}">
                {{ $slot }}
            </main>

            <!-- Mobile Bottom Navigation for Authenticated Students -->
            @if(!request()->routeIs('exam.run*'))
                <x-student-bottom-nav />
            @endif
        </div>
        <x-tawk-chat />
    </body>
</html>
