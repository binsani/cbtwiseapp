<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        $user = User::create($validated);
        $user->assignRole('user');

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
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
                    <span>🚀</span> Join 15,000+ Nigerian Scholars
                </div>
                <h2 class="text-3xl xl:text-4xl font-black font-heading tracking-tight leading-tight text-white">
                    Your 300+ JAMB score journey begins right here.
                </h2>
                <p class="text-slate-300 text-sm xl:text-base leading-relaxed">
                    Create your free account and start practicing real past questions with timed exam simulations and AI step-by-step guidance.
                </p>
            </div>

            <!-- Feature Points -->
            <div class="space-y-3.5">
                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-sm">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">50,000+ Verified Questions</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Comprehensive database for JAMB UTME, WAEC SSCE, and NECO exams.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-sm">
                    <div class="w-9 h-9 rounded-xl bg-teal-500/15 border border-teal-500/30 flex items-center justify-center text-teal-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Authentic 8-Key Simulator</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Build exam hall muscle memory and speed under real exam conditions.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.08] backdrop-blur-sm">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">100% Free Practice Tier</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Start solving questions instantly without paying a single kobo.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom: Student Testimonial Card & Stats -->
        <div class="relative z-10 pt-6 border-t border-white/10 space-y-4">
            <div class="p-4 rounded-2xl bg-white/[0.05] border border-white/10 backdrop-blur-md">
                <div class="flex items-center gap-1 text-amber-400 text-xs mb-1.5">
                    ★★★★★ <span class="text-slate-300 text-[11px] ml-1.5 font-bold">Scored 294 in JAMB UTME</span>
                </div>
                <p class="text-xs text-slate-300 italic leading-relaxed">
                    "I studied with CBTWise for 3 weeks before my UTME. The topic filtering and timed mode were game changers!"
                </p>
                <div class="flex items-center gap-2.5 mt-2.5">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white flex items-center justify-center font-black text-[10px]">
                        FU
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Fatima Umar</div>
                        <div class="text-[10px] text-slate-400">ABU Zaria Pharmacy Aspirant</div>
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

    <!-- Right Column: Registration Form -->
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
                    Create Your Account 🚀
                </h1>
                <p class="text-sm font-medium text-slate-500">
                    Get free instant access to thousands of CBT past questions.
                </p>
            </div>

            <!-- Registration Form -->
            <form wire:submit="register" class="space-y-4">
                
                <!-- Full Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input 
                            wire:model="name" 
                            id="name" 
                            type="text" 
                            name="name" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="e.g. Amina Bello"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all @error('name') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                        />
                    </div>
                    @error('name')
                        <p class="text-xs text-red-600 font-semibold flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

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
                            wire:model="email" 
                            id="email" 
                            type="email" 
                            name="email" 
                            required 
                            autocomplete="username"
                            placeholder="name@example.com"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                        />
                    </div>
                    @error('email')
                        <p class="text-xs text-red-600 font-semibold flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password with Show/Hide Toggle -->
                <div class="space-y-1.5" x-data="{ show: false }">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input 
                            wire:model="password" 
                            id="password" 
                            :type="show ? 'text' : 'password'" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="At least 8 characters"
                            class="w-full pl-10 pr-11 py-3 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
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
                    @error('password')
                        <p class="text-xs text-red-600 font-semibold flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password with Show/Hide Toggle -->
                <div class="space-y-1.5" x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input 
                            wire:model="password_confirmation" 
                            id="password_confirmation" 
                            :type="show ? 'text' : 'password'" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Repeat password"
                            class="w-full pl-10 pr-11 py-3 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all @error('password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
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
                    @error('password_confirmation')
                        <p class="text-xs text-red-600 font-semibold flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Terms disclaimer -->
                <p class="text-[11px] text-slate-500 leading-relaxed pt-1">
                    By clicking Register, you agree to our 
                    <a href="/terms" class="text-emerald-700 font-bold hover:underline">Terms of Service</a> and 
                    <a href="/privacy" class="text-emerald-700 font-bold hover:underline">Privacy Policy</a>.
                </p>

                <!-- Submit Button with Loading Indicator -->
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-3.5 px-6 rounded-2xl font-extrabold text-sm text-white bg-gradient-to-r from-emerald-600 via-emerald-700 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2 group disabled:opacity-75 disabled:cursor-not-allowed mt-2"
                >
                    <span wire:loading.remove>Create Free Account →</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Creating Account...
                    </span>
                </button>
            </form>

            <!-- Switch to Login -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs font-semibold text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" wire:navigate class="font-extrabold text-emerald-700 hover:text-emerald-800 hover:underline ml-1">
                        Sign In here →
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
                    Instant Free Practice
                </span>
            </div>
        </div>
    </div>
</div>
