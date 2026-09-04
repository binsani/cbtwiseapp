<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 font-sans">
    
    <!-- Top Header -->
    <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-heading">Settings</h1>
            <p class="text-slate-500 text-sm mt-1">Manage your account profile details and preferences.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition-all shadow-sm">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Sidebar Menu -->
        <aside class="w-full lg:w-64 bg-white border border-slate-100 rounded-3xl p-6 space-y-2 flex-shrink-0 shadow-sm">
            <p class="text-xs font-black uppercase tracking-wider text-slate-400 mb-4 px-3">Account Settings</p>
            <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                👤 Profile Details
            </a>
            <a href="{{ route('account.security') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                🔒 Change Password
            </a>
            <a href="{{ route('account.subscription') }}" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 font-extrabold rounded-2xl text-sm transition-all">
                💳 Subscription Plan
            </a>
            <a href="{{ route('account.purchase-codes') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                🎟️ Redeem Vouchers
            </a>
            <div class="border-t border-slate-100 pt-2 mt-2">
                <a href="{{ route('account.delete') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-red-650 font-bold rounded-2xl text-sm transition-all">
                    ⚠️ Close Account
                </a>
            </div>
        </aside>

        <!-- Subscription Card -->
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 font-heading border-b border-slate-50 pb-2 mb-6">Subscription Plan</h3>

                @if (session()->has('message'))
                    <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-bold shadow-sm mb-6">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="bg-slate-50 border border-slate-100/60 rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="inline-block px-3 py-1 bg-slate-200 text-slate-700 text-[10px] font-black uppercase tracking-wider rounded-full mb-2">Current Plan</span>
                            <h4 class="text-2xl font-black text-slate-900">Free Tier</h4>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-black text-slate-900">₦0</span>
                            <span class="text-slate-500 text-xs">/ forever</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mb-6 font-medium">You are currently on the free plan which includes limited access to practice tests and basic analytics.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">✓</span>
                            Access to 5 free mock exams
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">✓</span>
                            Basic performance tracking
                        </li>
                    </ul>
                </div>

                <div class="mt-8 border-t border-slate-100 pt-8">
                    <h4 class="text-lg font-bold text-slate-900 mb-4">Upgrade to Premium</h4>
                    <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <h5 class="font-black text-emerald-900 mb-1">Unlock Unlimited Potential</h5>
                            <p class="text-xs text-emerald-700">Get access to all past questions, advanced analytics, timed mock exams, and topic-by-topic breakdowns.</p>
                        </div>
                        <button wire:click="upgrade" class="whitespace-nowrap px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-md transition-colors flex-shrink-0">
                            Upgrade Now &rarr;
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
