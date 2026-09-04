<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
