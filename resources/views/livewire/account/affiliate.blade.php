<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900">{{ __('Affiliate Program') }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ __('Earn 20% recurring commission on every student you refer to CBTWise.') }}</p>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if (!$isApplied)
            {{-- Landing / Apply Section --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-8 sm:p-12 text-center shadow-sm max-w-3xl mx-auto">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 text-3xl mb-6">
                    💰
                </div>
                <h2 class="text-2xl font-black text-slate-900 mb-3">{{ __('Become a CBTWise Partner') }}</h2>
                <p class="text-slate-600 text-sm leading-relaxed max-w-lg mx-auto mb-8">
                    {{ __('Partner with Nigeria\'s fastest growing CBT preparation platform. Share your link with students, teachers, or parents and get paid 20% on all subscriptions they purchase.') }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-left max-w-xl mx-auto mb-10">
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <div class="text-emerald-600 font-extrabold text-sm mb-1">1. {{ __('Get Link') }}</div>
                        <div class="text-xs text-slate-500">{{ __('Generate your unique referral link instantly.') }}</div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <div class="text-emerald-600 font-extrabold text-sm mb-1">2. {{ __('Share') }}</div>
                        <div class="text-xs text-slate-500">{{ __('Post on WhatsApp, Facebook, groups or blogs.') }}</div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <div class="text-emerald-600 font-extrabold text-sm mb-1">3. {{ __('Earn ₦₦₦') }}</div>
                        <div class="text-xs text-slate-500">{{ __('Get paid direct to your bank account monthly.') }}</div>
                    </div>
                </div>

                <button wire:click="apply" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl shadow-lg transition-all hover:-translate-y-0.5">
                    🚀 {{ __('Activate My Affiliate Account') }}
                </button>
            </div>
        @else
            {{-- Dashboard Dashboard --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left/Main Stats and tables --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Referral Link Widget --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-sm font-black text-slate-900 mb-3">{{ __('Your Referral Link') }}</h3>
                        
                        <div class="flex items-center gap-2" x-data="{ copied: false, link: '{{ url('/?ref=' . Auth::user()->referral_code) }}' }">
                            <input 
                                type="text" 
                                readonly 
                                x-model="link"
                                class="flex-1 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm text-slate-700 font-medium focus:outline-none"
                            >
                            <button 
                                @click="navigator.clipboard.writeText(link); copied = true; setTimeout(() => copied = false, 2000)"
                                class="px-5 py-3 rounded-xl text-sm font-extrabold text-white transition-all flex items-center gap-1.5"
                                :class="copied ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-slate-900 hover:bg-slate-800'"
                            >
                                <span x-text="copied ? 'Copied! ✓' : 'Copy Link'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">{{ __('Clicks') }}</span>
                            <span class="text-2xl font-black text-slate-900 block mt-2">{{ number_format($clicksCount) }}</span>
                        </div>
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">{{ __('Referred Sales') }}</span>
                            <span class="text-2xl font-black text-slate-900 block mt-2">{{ $conversions->count() }}</span>
                        </div>
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">{{ __('Pending Commission') }}</span>
                            <span class="text-2xl font-black text-amber-600 block mt-2">₦{{ number_format($affiliate->pendingEarnings(), 2) }}</span>
                        </div>
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">{{ __('Available Balance') }}</span>
                            <span class="text-2xl font-black text-emerald-600 block mt-2">₦{{ number_format($affiliate->balance_ngn, 2) }}</span>
                        </div>
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm col-span-2 sm:col-span-1">
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">{{ __('Total Earned') }}</span>
                            <span class="text-2xl font-black text-slate-900 block mt-2">₦{{ number_format($affiliate->total_earned_ngn, 2) }}</span>
                        </div>
                    </div>

                    {{-- Conversions List --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-lg font-black text-slate-900 mb-4">{{ __('Referred Subscriptions') }}</h3>
                        
                        @if($conversions->isEmpty())
                            <div class="text-center py-8 text-slate-400 text-sm">
                                {{ __('No referrals recorded yet. Share your link to start earning!') }}
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase">
                                            <th class="pb-3">{{ __('Referred User') }}</th>
                                            <th class="pb-3">{{ __('Commission') }}</th>
                                            <th class="pb-3">{{ __('Date') }}</th>
                                            <th class="pb-3">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                                        @foreach($conversions as $conv)
                                            <tr>
                                                <td class="py-3.5">{{ $conv->referredUser->name }}</td>
                                                <td class="py-3.5 font-bold text-emerald-600">₦{{ number_format($conv->commission_ngn, 2) }}</td>
                                                <td class="py-3.5 text-xs text-slate-500">{{ $conv->converted_at->format('M d, Y H:i') }}</td>
                                                <td class="py-3.5">
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider 
                                                        {{ $conv->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                                        {{ $conv->status === 'approved' ? 'bg-blue-50 text-blue-700' : '' }}
                                                        {{ $conv->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                                        {{ $conv->status === 'reversed' ? 'bg-rose-50 text-rose-700' : '' }}
                                                    ">
                                                        {{ $conv->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right Sidebar - Payout Request and History --}}
                <div class="space-y-8">
                    
                    {{-- Payout Request Box --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-lg font-black text-slate-900 mb-4">{{ __('Request Payout') }}</h3>

                        @if ($affiliate->balance_ngn < 5000)
                            <div class="p-4 bg-slate-50 rounded-2xl text-xs text-slate-500 leading-relaxed">
                                💡 {{ __('Payouts can be requested once your available balance reaches at least ₦5,000.') }}
                            </div>
                        @else
                            <form wire:submit.prevent="requestPayout" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Select Bank') }}</label>
                                    <select 
                                        wire:model="bank_code" 
                                        class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                                    >
                                        <option value="">{{ __('-- Choose Bank --') }}</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('bank_code') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Account Number') }}</label>
                                    <input 
                                        type="text" 
                                        wire:model.live="account_number" 
                                        maxlength="10"
                                        placeholder="e.g. 0123456789"
                                        class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                                    >
                                    @error('account_number') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Account Name') }}</label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            wire:model="account_name" 
                                            readonly
                                            placeholder="{{ __('Enter bank & account to resolve') }}"
                                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none {{ $resolvedName ? 'text-emerald-700 bg-emerald-50/50' : '' }}"
                                        >
                                        @if($isResolving)
                                            <span class="absolute right-3 top-3 text-xs text-slate-400 animate-pulse">Resolving...</span>
                                        @endif
                                    </div>
                                    @error('account_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Payout Amount (₦)') }}</label>
                                    <input 
                                        type="number" 
                                        wire:model="payout_amount" 
                                        min="5000"
                                        max="{{ $affiliate->balance_ngn }}"
                                        class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                                    >
                                    @error('payout_amount') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <button 
                                    type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl transition-all shadow-md"
                                >
                                    {{ __('Submit Payout Request') }}
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Payout History --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-lg font-black text-slate-900 mb-4">{{ __('Payout History') }}</h3>
                        
                        @if($payouts->isEmpty())
                            <div class="text-center py-6 text-slate-400 text-xs">
                                {{ __('No payouts requested yet.') }}
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($payouts as $pay)
                                    <div class="p-3 bg-slate-50 rounded-2xl flex items-center justify-between">
                                        <div>
                                            <span class="font-extrabold text-sm text-slate-800 block">₦{{ number_format($pay->amount_ngn, 2) }}</span>
                                            <span class="text-[10px] text-slate-400 block mt-0.5">{{ $pay->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider
                                                {{ $pay->status === 'success' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                                {{ $pay->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                                {{ $pay->status === 'processing' ? 'bg-blue-50 text-blue-700' : '' }}
                                                {{ $pay->status === 'failed' ? 'bg-rose-50 text-rose-700' : '' }}
                                            ">
                                                {{ $pay->status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Marketing Assets --}}
                    <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-sm">
                        <h3 class="font-black text-sm mb-3 text-slate-300">📣 {{ __('Marketing Assets') }}</h3>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4">
                            {{ __('Download high-converting banner designs to post on your status, website, or social media channels.') }}
                        </p>
                        <div class="space-y-2">
                            <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-emerald-600 transition-colors text-xs font-bold">
                                🖼️ {{ __('Flyer Banner (Square)') }}
                                <span class="text-[10px] bg-slate-700 px-2 py-0.5 rounded-full">PNG</span>
                            </a>
                            <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-emerald-600 transition-colors text-xs font-bold">
                                📱 {{ __('WhatsApp Status Slide') }}
                                <span class="text-[10px] bg-slate-700 px-2 py-0.5 rounded-full">PNG</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @endif

    </div>
</div>
