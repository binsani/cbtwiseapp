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
            <a href="{{ route('account.purchase-codes') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                🎟️ Redeem Vouchers
            </a>
            <div class="border-t border-slate-100 pt-2 mt-2">
                <a href="{{ route('account.delete') }}" class="flex items-center gap-3 px-4 py-3 bg-red-50 text-red-700 font-extrabold rounded-2xl text-sm transition-all">
                    ⚠️ Close Account
                </a>
            </div>
        </aside>

        <!-- Delete Account Card -->
        <div class="flex-1 bg-white rounded-3xl border border-red-100 p-8 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-slate-900 font-heading border-b border-slate-50 pb-2 text-red-600">Delete Account</h3>
            
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                <p class="text-xs text-red-800 font-bold mb-1">Warning: This action is irreversible.</p>
                <p class="text-xs text-red-700 leading-relaxed">Once your account is deleted, all of your resources, practice history, and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
            </div>

            @if (session()->has('error'))
                <div class="p-4 bg-red-100 border border-red-200 text-red-800 rounded-2xl text-xs font-bold shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit="deleteAccount" class="space-y-6 pt-4">
                
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-2">Confirm Password</label>
                    <p class="text-[10px] text-slate-500 mb-2">Please enter your password to confirm you would like to permanently delete your account.</p>
                    <input type="password" id="password" wire:model="password" class="w-full max-w-md px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-red-500 text-xs">
                    @error('password') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold rounded-xl shadow-md transition-colors">
                    Permanently Delete Account
                </button>
            </form>
        </div>

    </div>
</div>
