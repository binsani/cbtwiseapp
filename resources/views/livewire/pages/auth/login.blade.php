<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen w-full flex flex-col lg:flex-row bg-slate-50">
    
    <!-- Left Column: Inspiring Brand Hero (Desktop & Tablet) -->
    <div class="hidden lg:flex lg:w-1/2 xl:w-5/12 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 p-12 flex-col justify-between relative overflow-hidden text-white flex-shrink-0">
        <!-- Ambient decorative elements -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:28px_28px] opacity-10 pointer-events-none"></div>

        <!-- Top: Logo & Back Link -->
        <div class="relative z-10 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <img src="/logo.png" alt="CBTWise Logo" class="h-10 w-10 rounded-xl shadow-md group-hover:scale-105 transition-transform bg-white p-1">
                <div>
                    <span class="text-2xl font-black tracking-tight font-heading bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-300">
                        CBTWise
                    </span>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-emerald-400/80">Exam Mastery</span>
                </div>
            </a>
            <a href="/" class="text-xs font-bold text-slate-400 hover:text-white flex items-center gap-1.5 transition-colors bg-white/5 hover:bg-white/10 px-3.5 py-2 rounded-xl border border-white/10">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Home
            </a>
        </div>

        <!-- Middle: Punchy Value Prop & Pillars -->
        <div class="relative z-10 my-auto py-10 space-y-8">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-extrabold tracking-wide uppercase">
                    <span>✨</span> Nigeria's Premier CBT Engine
                </div>
                <h2 class="text-3xl xl:text-4xl font-black font-heading tracking-tight leading-tight text-white">
                    Master UTME, WAEC & NECO with absolute confidence.
                </h2>
                <p class="text-slate-300 text-sm xl:text-base leading-relaxed">
                    Practice authentic past questions with timed exam simulations, real-time scoring, and instant AI tutor explanations.
                </p>
            </div>

            <!-- Feature Points -->
            <div class="space-y-3.5">
                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-sm">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Authentic CBT Simulations</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Real 8-key navigation and timer identical to actual JAMB testing halls.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-sm">
                    <div class="w-9 h-9 rounded-xl bg-teal-500/15 border border-teal-500/30 flex items-center justify-center text-teal-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Step-by-Step AI Explanations</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Understand why every option is correct with instant detailed working.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-sm">
                    <div class="w-9 h-9 rounded-xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Smart Weakness Heatmaps</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Pinpoint your exact weak subjects and topics before exam day.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom: Student Testimonial Card & Stats -->
        <div class="relative z-10 pt-6 border-t border-white/10 space-y-4">
            <div class="p-4 rounded-2xl bg-white/[0.05] border border-white/10 backdrop-blur-md">
                <div class="flex items-center gap-1 text-amber-400 text-xs mb-1.5">
                    ★★★★★ <span class="text-slate-300 text-[11px] ml-1.5 font-bold">Scored 312 in JAMB UTME</span>
                </div>
                <p class="text-xs text-slate-300 italic leading-relaxed">
                    "CBTWise timed mode helped me eliminate exam hall panic. The questions were identical to what came out in my actual UTME!"
                </p>
                <div class="flex items-center gap-2.5 mt-2.5">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white flex items-center justify-center font-black text-[10px]">
                        CO
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Chinedu Okafor</div>
                        <div class="text-[10px] text-slate-400">UNILAG Medicine Aspirant</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400 px-2">
                <span>📚 <strong>50,000+</strong> Questions</span>
                <span>🎯 <strong>98.4%</strong> Pass Rate</span>
                <span>⚡ <strong>15k+</strong> Scholars</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Authentication Form -->
    <div class="w-full lg:w-1/2 xl:w-7/12 flex items-center justify-center p-6 sm:p-12 xl:p-16 relative">
        <!-- Subtle background glow for right side -->
        <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md space-y-8 relative z-10">
            
            <!-- Mobile Brand Bar & Navigation -->
            <div class="flex items-center justify-between pb-2">
                <a href="/" class="flex items-center gap-2.5 group">
                    <img src="/logo.png" alt="CBTWise Logo" class="h-9 w-9 rounded-xl shadow-sm group-hover:scale-105 transition-transform bg-white p-1 border border-slate-100">
                    <span class="text-xl font-black tracking-tight font-heading bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-600">
                        CBTWise
                    </span>
                </a>
                <a href="/" class="text-xs font-bold text-slate-500 hover:text-emerald-600 flex items-center gap-1.5 transition-colors bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Home
                </a>
            </div>

            <!-- Form Header -->
            <div class="space-y-2">
                <h1 class="text-3xl font-black font-heading tracking-tight text-slate-950">
                    Welcome Back 👋
                </h1>
                <p class="text-sm font-medium text-slate-500">
                    Log in with your credentials to resume your CBT practice sessions.
                </p>
            </div>

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form wire:submit="login" class="space-y-5">
                
                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </div>
                        <input 
                            wire:model="form.email" 
                            id="email" 
                            type="email" 
                            name="email" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="name@example.com"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all @error('form.email') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                        />
                    </div>
                    @error('form.email')
                        <p class="text-xs text-red-600 font-semibold flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password with Show/Hide Toggle -->
                <div class="space-y-1.5" x-data="{ show: false }">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Password <span class="text-red-500">*</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input 
                            wire:model="form.password" 
                            id="password" 
                            :type="show ? 'text' : 'password'" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-11 py-3 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all @error('form.password') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                        />
                        <button 
                            type="button" 
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                            title="Toggle password visibility"
                        >
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('form.password')
                        <p class="text-xs text-red-600 font-semibold flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input 
                            wire:model="form.remember" 
                            id="remember" 
                            type="checkbox" 
                            class="w-4 h-4 rounded-md border-slate-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 transition-colors" 
                            name="remember"
                        >
                        <span class="text-xs font-semibold text-slate-600">Keep me logged in</span>
                    </label>
                </div>

                <!-- Submit Button with Loading Indicator -->
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-3.5 px-6 rounded-2xl font-extrabold text-sm text-white bg-gradient-to-r from-emerald-600 via-emerald-700 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2 group disabled:opacity-75 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove>Sign In to Dashboard →</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Authenticating...
                    </span>
                </button>
            </form>

            <!-- Switch to Register -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs font-semibold text-slate-500">
                    Don't have an account yet?
                    <a href="{{ route('register') }}" wire:navigate class="font-extrabold text-emerald-700 hover:text-emerald-800 hover:underline ml-1">
                        Create one for free →
                    </a>
                </p>
            </div>

            <!-- Trust / Security Footer -->
            <div class="pt-6 border-t border-slate-100/80 flex items-center justify-center gap-6 text-[11px] text-slate-400 font-medium">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    256-bit SSL Encrypted
                </span>
                <span>&bull;</span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                    Verified Past Questions
                </span>
            </div>
        </div>
    </div>
</div>
