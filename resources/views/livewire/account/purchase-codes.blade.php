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
            <a href="{{ route('account.subscription') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                💳 Subscription Plan
            </a>
            <a href="{{ route('account.purchase-codes') }}" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 font-extrabold rounded-2xl text-sm transition-all">
                🎟️ Redeem Vouchers
            </a>
            <div class="border-t border-slate-100 pt-2 mt-2">
                <a href="{{ route('account.delete') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-red-650 font-bold rounded-2xl text-sm transition-all">
                    ⚠️ Close Account
                </a>
            </div>
        </aside>

        <!-- Redeem Form Card -->
        <div class="flex-1 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-slate-900 font-heading border-b border-slate-50 pb-2">Redeem Vouchers</h3>
            <p class="text-slate-500 text-xs">Have a scratch card or purchase code? Enter it below to activate your premium subscription access.</p>

            @if (session()->has('message'))
                <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 bg-red-50 border border-red-100 text-red-800 rounded-2xl text-xs font-bold shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit="redeemCode" class="space-y-6 pt-4">
                
                <div>
                    <label for="code" class="block text-xs font-bold text-slate-700 mb-2">Activation Code / PIN</label>
                    <input type="text" id="code" wire:model="code" class="w-full max-w-md px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 font-mono text-sm tracking-widest uppercase placeholder-slate-300" placeholder="XXXX-XXXX-XXXX">
                    @error('code') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl shadow-md transition-colors">
                    Redeem Code
                </button>
            </form>
        </div>

    </div>
</div>
