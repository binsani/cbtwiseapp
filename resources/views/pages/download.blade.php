@extends('layouts.public')

@section('title', 'Download CBTwise Desktop App — Practice JAMB, WAEC & NECO Offline')
@section('meta_description', 'Download the CBTwise Desktop App for Windows, macOS, and Linux. Practice authentic CBT past questions completely offline with our 8-key exam simulator.')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "SoftwareApplication",
  "name": "CBTwise Desktop",
  "operatingSystem": "Windows 10, Windows 11, macOS, Linux",
  "applicationCategory": "EducationalApplication",
  "offers": {
    "@@type": "Offer",
    "price": "0",
    "priceCurrency": "NGN"
  },
  "description": "AI-Powered CBT Exam Preparation Platform with 100% offline practice mode for JAMB, WAEC, NECO and Post-UTME."
}
</script>
@endsection

@section('content')
<div class="bg-gradient-to-b from-slate-50 via-white to-slate-50 py-16 sm:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-widest mb-6">
                ⚡ 100% Offline Practice Available
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-950 font-heading tracking-tight leading-tight">
                Practice Without Limits.<br />
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700">Download CBTwise Desktop.</span>
            </h1>
            <p class="mt-6 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                No internet? No problem. Practice past questions, master weak areas, and simulate authentic JAMB, WAEC, and NECO exams directly on your computer.
            </p>
        </div>

        <!-- Download Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-20">
            
            <!-- Windows (Featured) -->
            <div class="relative bg-white rounded-3xl p-8 border-2 border-emerald-500 shadow-xl shadow-emerald-500/10 flex flex-col justify-between group hover:-translate-y-1 transition-all">
                <div class="absolute -top-3.5 right-6 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-[10px] font-black uppercase tracking-widest py-1 px-3.5 rounded-full shadow-sm">
                    Recommended
                </div>

                <div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 border border-emerald-100">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M0 3.449L9.75 2.1v9.451H0m10.949-9.602L24 0v11.401H10.949M0 12.6h9.75v9.451L0 20.699M10.949 12.6H24V24l-12.95-1.801"/>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-black text-slate-950 font-heading mb-2">
                        Windows
                    </h2>
                    <p class="text-xs text-slate-500 mb-6 font-medium leading-relaxed">
                        Windows 10 and 11 (64-bit & ARM64). Includes built-in offline exam engine and 1-click automatic setup.
                    </p>

                    <div class="space-y-2 mb-8 text-xs font-semibold text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Offline & Online dual-mode</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>8-key official CBT keyboard simulator</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Auto-updates with newest questions</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a 
                        href="{{ route('download.windows') }}" 
                        class="w-full py-4 px-6 rounded-2xl font-extrabold text-sm text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download for Windows</span>
                    </a>
                    <p class="text-[11px] text-center text-slate-400 mt-2.5 font-medium">
                        Setup .exe (~150MB) &bull; Verified Safe
                    </p>
                </div>
            </div>

            <!-- macOS -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between group hover:border-slate-300 hover:-translate-y-1 transition-all">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.15c.66-.8 1.1-1.92.98-3.04-1 .04-2.19.67-2.9 1.5-.58.67-1.09 1.77-.95 2.85 1.12.09 2.22-.55 2.87-1.31"/>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-black text-slate-950 font-heading mb-2">
                        macOS
                    </h2>
                    <p class="text-xs text-slate-500 mb-6 font-medium leading-relaxed">
                        macOS 11.0 Big Sur or later. Compatible with Apple Silicon (M1/M2/M3) and Intel Macs.
                    </p>

                    <div class="space-y-2 mb-8 text-xs font-semibold text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Universal binary for Apple Silicon & Intel</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Native macOS desktop integration</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Direct access to cbtwise.com.ng</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a 
                        href="https://github.com/binsani/cbtwiseapp/releases" 
                        target="_blank" 
                        rel="noopener"
                        class="w-full py-4 px-6 rounded-2xl font-extrabold text-sm text-slate-900 bg-slate-100 hover:bg-slate-200 transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download for macOS</span>
                    </a>
                    <p class="text-[11px] text-center text-slate-400 mt-2.5 font-medium">
                        .dmg package &bull; Apple Silicon & Intel
                    </p>
                </div>
            </div>

            <!-- Linux -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between group hover:border-slate-300 hover:-translate-y-1 transition-all">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 border border-amber-100">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.002 0c-3.18 0-4.996 2.05-5.32 4.195-.125.828-.088 1.95.105 3.013C5.556 8.04 4.5 9.4 4.5 11.25c0 1.25.5 2.37 1.34 3.16-.27 1.07-.34 2.22-.34 3.09 0 3.32 2.91 6.5 6.502 6.5s6.502-3.18 6.502-6.5c0-.87-.07-2.02-.34-3.09.84-.79 1.34-1.91 1.34-3.16 0-1.85-1.056-3.21-2.287-4.042.193-1.063.23-2.185.105-3.013C16.998 2.05 15.182 0 12.002 0z"/>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-black text-slate-950 font-heading mb-2">
                        Linux
                    </h2>
                    <p class="text-xs text-slate-500 mb-6 font-medium leading-relaxed">
                        Ubuntu, Debian, Fedora, Arch and all major Linux distributions. Available as AppImage and .deb.
                    </p>

                    <div class="space-y-2 mb-8 text-xs font-semibold text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Portable AppImage (Run anywhere)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Debian / Ubuntu (.deb) package</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Full offline practice support</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a 
                        href="https://github.com/binsani/cbtwiseapp/releases" 
                        target="_blank" 
                        rel="noopener"
                        class="w-full py-4 px-6 rounded-2xl font-extrabold text-sm text-slate-900 bg-slate-100 hover:bg-slate-200 transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download for Linux</span>
                    </a>
                    <p class="text-[11px] text-center text-slate-400 mt-2.5 font-medium">
                        .AppImage &bull; .deb package
                    </p>
                </div>
            </div>

        </div>

        <!-- Features Showcase -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-sm max-w-5xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-950 font-heading">
                    Why Use the CBTwise Desktop App?
                </h3>
                <p class="mt-3 text-sm text-slate-500 font-medium">
                    Engineered specifically for Nigerian students facing internet connectivity or power challenges.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl mb-3">⚡</div>
                    <h4 class="font-extrabold text-slate-900 text-sm mb-1.5 font-heading">Zero Data Required</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Practice tens of thousands of past questions without using your mobile data.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl mb-3">🎯</div>
                    <h4 class="font-extrabold text-slate-900 text-sm mb-1.5 font-heading">Official 8-Key CBT</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Get familiar with the real A, B, C, D, N, P, S, R keyboard controls used in official test centers.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl mb-3">🧠</div>
                    <h4 class="font-extrabold text-slate-900 text-sm mb-1.5 font-heading">Step-by-Step AI</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Clear, comprehensive solution walkthroughs so you never repeat the same mistake twice.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl mb-3">🔄</div>
                    <h4 class="font-extrabold text-slate-900 text-sm mb-1.5 font-heading">Seamless Sync</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Connect to the internet whenever convenient to sync your exam history with your online account.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
