<div class="max-w-4xl mx-auto py-4 px-4 sm:px-6 lg:px-8 font-sans space-y-8">
    
    <!-- Navigation Tabs -->
    <x-dashboard-nav />

    <!-- Header -->
    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-heading">Refer & Earn</h1>
            <p class="text-slate-500 text-sm mt-1">Invite your classmates to CBTWise. Get rewarded when they upgrade!</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50 transition-colors">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        
        <!-- Code sharing box -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl space-y-6">
                <h3 class="text-lg font-bold font-heading">Your Referral Code</h3>
                <p class="text-xs text-slate-350 leading-relaxed">
                    Share this code with your classmates. When they register using your code, they get N100 off their first payment, and you get rewarded.
                </p>
                
                <div class="bg-white/10 rounded-2xl p-4 flex items-center justify-between border border-white/10">
                    <span class="text-2xl font-black font-heading tracking-wider select-all">
                        {{ $referralCode }}
                    </span>
                    <button 
                        onclick="navigator.clipboard.writeText('{{ $referralCode }}'); alert('Code copied to clipboard!');"
                        class="px-3 py-1.5 bg-white text-slate-900 text-xs font-extrabold rounded-lg hover:bg-slate-100"
                    >
                        Copy
                    </button>
                </div>
            </div>
        </div>

        <!-- Invites history log -->
        <div class="md:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-50 pb-2">Your Successful Referrals</h3>
            
            <div class="space-y-4">
                @forelse($referrals as $invite)
                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold">
                                👤
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">
                                    {{ $invite->referredUser?->name ?? 'Scholar Registered' }}
                                </h4>
                                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                    Joined {{ $invite->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                            {{ $invite->rewarded_at ? 'Reward Disbursed' : 'Pending Upgrade' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        <span class="text-2xl block mb-2">🤝</span>
                        You haven't referred anyone yet. Share your code to start earning!
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
