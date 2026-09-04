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
            <a href="{{ route('account.security') }}" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 font-extrabold rounded-2xl text-sm transition-all">
                🔒 Change Password
            </a>
            <a href="{{ route('account.subscription') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
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

        <!-- Profile Form Card -->
        <div class="flex-1 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-slate-900 font-heading border-b border-slate-50 pb-2">Change Password</h3>

            @if (session()->has('message'))
                <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="updatePassword" class="space-y-6">
                
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-2">Current Password</label>
                    <input type="password" id="current_password" wire:model="current_password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                    @error('current_password') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-2">New Password</label>
                        <input type="password" id="password" wire:model="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                        @error('password') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" id="password_confirmation" wire:model="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                    </div>
                </div>

                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-md transition-colors">
                    Update Password
                </button>
            </form>
        </div>

    </div>
</div>
