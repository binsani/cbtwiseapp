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

<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12" style="background: #f0faf4;">

    <!-- Logo -->
    <div class="flex flex-col items-center mb-8">
        <a href="/" class="flex flex-col items-center gap-2 group">
            <img src="/logo.png" alt="CBTWise Logo" class="h-12 w-12 rounded-2xl shadow-sm bg-white p-1.5 border border-emerald-100 group-hover:scale-105 transition-transform">
            <span class="text-sm font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-emerald-700 to-teal-600">CBTWise</span>
        </a>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md">

        <!-- Heading -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Welcome back</h1>
            <p class="mt-1.5 text-sm text-slate-500 font-medium">Log in to continue your exam prep</p>
        </div>

        <!-- Tab Switcher -->
        <div class="flex bg-slate-100 rounded-2xl p-1 mb-8">
            <button type="button" class="flex-1 py-2.5 text-sm font-bold rounded-xl bg-white text-slate-900 shadow-sm transition-all">
                Login
            </button>
            <a href="{{ route('redeem') }}" class="flex-1 py-2.5 text-sm font-semibold rounded-xl text-slate-500 hover:text-slate-700 transition-colors text-center">
                Purchase Code
            </a>
        </div>

        <!-- Session Status Alert -->
        @if (session('status'))
            <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Login Form -->
        <form wire:submit="login" class="space-y-5">

            <!-- Email -->
            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-semibold text-slate-800">Email</label>
                <input
                    wire:model="form.email"
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="w-full px-4 py-3 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all outline-none @error('form.email') border-red-300 focus:border-red-400 @enderror"
                />
                @error('form.email')
                    <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-1.5" x-data="{ show: false }">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-semibold text-slate-800">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <input
                        wire:model="form.password"
                        id="password"
                        :type="show ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 pr-11 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all outline-none @error('form.password') border-red-300 focus:border-red-400 @enderror"
                    />
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                    >
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
                @error('form.password')
                    <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Log In Button -->
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full py-3.5 px-6 rounded-full font-bold text-sm text-white transition-all flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed"
                style="background: #2d6a4f;"
                onmouseover="this.style.background='#1b4332'" onmouseout="this.style.background='#2d6a4f'"
            >
                <span wire:loading.remove>Log in</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Signing in...
                </span>
            </button>
        </form>

        <!-- Sign Up Link -->
        <p class="mt-6 text-center text-sm text-slate-500 font-medium">
            Don't have an account?
            <a href="{{ route('register') }}" wire:navigate class="font-bold text-emerald-700 hover:text-emerald-800 transition-colors ml-1">Sign up</a>
        </p>
    </div>
</div>
