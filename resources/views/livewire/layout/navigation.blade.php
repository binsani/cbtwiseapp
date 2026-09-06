<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('exam.setup')" :active="request()->routeIs('exam.setup*')" wire:navigate class="text-emerald-600 font-bold">
                            {{ __('Practice CBT') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard.history')" :active="request()->routeIs('dashboard.history*')" wire:navigate>
                            {{ __('History') }}
                        </x-nav-link>
                    @endauth
                    <x-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')" wire:navigate>
                        {{ __('Pricing') }}
                    </x-nav-link>
                    <x-nav-link :href="route('redeem')" :active="request()->routeIs('redeem')" wire:navigate>
                        {{ __('Redeem Code') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:space-x-3">
                @auth
                    <!-- Notification Bell -->
                    @php
                        $unreadBellCount = \App\Models\UserNotification::where('user_id', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    <a href="{{ route('dashboard.notifications') }}" wire:navigate 
                       class="relative p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                       title="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if($unreadBellCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
                        @endif
                    </a>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()?->name ?? '']) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('account.profile')" wire:navigate>
                                {{ __('My Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('dashboard.bookmarks')" wire:navigate>
                                {{ __('Bookmarks') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('dashboard.streak')" wire:navigate>
                                {{ __('Study Streak') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('dashboard.leaderboard')" wire:navigate>
                                {{ __('Leaderboard') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('account.subscription')" wire:navigate>
                                {{ __('Subscription') }}
                            </x-dropdown-link>

                            @role('admin')
                                <div class="border-t border-gray-100 my-1"></div>
                                <x-dropdown-link :href="route('admin.dashboard')" wire:navigate>
                                    {{ __('Admin Dashboard') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.purchase-codes')" wire:navigate>
                                    {{ __('Purchase Codes') }}
                                </x-dropdown-link>
                            @endrole

                            @hasanyrole('admin|moderator')
                                <x-dropdown-link :href="route('admin.reports')" wire:navigate>
                                    {{ __('Moderation Queue') }}
                                </x-dropdown-link>
                            @endhasanyrole

                            <div class="border-t border-gray-100 my-1"></div>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-emerald-600 transition" wire:navigate>{{ __('Log in') }}</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition" wire:navigate>{{ __('Register') }}</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('exam.setup')" :active="request()->routeIs('exam.setup*')" wire:navigate class="text-emerald-600 font-bold">
                    {{ __('Practice CBT') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.history')" :active="request()->routeIs('dashboard.history*')" wire:navigate>
                    {{ __('Session History') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.bookmarks')" :active="request()->routeIs('dashboard.bookmarks')" wire:navigate>
                    {{ __('Bookmarks') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.notifications')" :active="request()->routeIs('dashboard.notifications')" wire:navigate>
                    {{ __('Notifications') }}
                </x-responsive-nav-link>
            @endauth
            <x-responsive-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')" wire:navigate>
                {{ __('Pricing') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('redeem')" :active="request()->routeIs('redeem')" wire:navigate>
                {{ __('Redeem Code') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()?->name ?? '']) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()?->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('account.profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @role('admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')" wire:navigate>
                            {{ __('Admin Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.purchase-codes')" wire:navigate>
                            {{ __('Purchase Codes') }}
                        </x-responsive-nav-link>
                    @endrole

                    @hasanyrole('admin|moderator')
                        <x-responsive-nav-link :href="route('admin.reports')" wire:navigate>
                            {{ __('Moderation Queue') }}
                        </x-responsive-nav-link>
                    @endhasanyrole

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            @else
                <div class="px-4 py-2 space-y-2">
                    <x-responsive-nav-link :href="route('login')" wire:navigate>
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" wire:navigate>
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
