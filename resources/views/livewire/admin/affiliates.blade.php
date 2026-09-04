<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">{{ __('Manage Affiliates') }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ __('Review user partner applications, approve payouts, and monitor performance.') }}</p>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pending Payout Requests Alert/Section --}}
        @if($pendingPayouts->isNotEmpty())
            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 mb-8 shadow-sm">
                <h3 class="text-base font-black text-amber-900 mb-4 flex items-center gap-2">
                    ⚠️ {{ __('Pending Payout Requests') }} ({{ $pendingPayouts->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pendingPayouts as $payout)
                        <div class="bg-white border border-amber-100 rounded-2xl p-4 flex justify-between items-start">
                            <div class="space-y-1">
                                <span class="font-extrabold text-sm text-slate-900 block">{{ $payout->affiliate->user->name }}</span>
                                <span class="text-xs text-slate-500 block">{{ $payout->affiliate->user->email }}</span>
                                <span class="text-xs font-bold text-emerald-600 block mt-1">₦{{ number_format($payout->amount_ngn, 2) }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ __('Requested:') }} {{ $payout->created_at->format('M d, Y') }}</span>
                                @if($payout->affiliate->account_number)
                                    <div class="mt-2 text-xs bg-slate-50 p-2 rounded-xl text-slate-600 font-medium">
                                        🏦 {{ $payout->affiliate->account_name }} ({{ $payout->affiliate->account_number }}) - Code: {{ $payout->affiliate->bank_code }}
                                    </div>
                                @endif
                            </div>
                            <button 
                                wire:click="openPayoutModal({{ $payout->affiliate->id }})"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all"
                            >
                                ✅ {{ __('Pay / Record') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Filters & Table --}}
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Search by name or email..."
                    class="flex-1 bg-slate-50 border border-slate-100 rounded-2xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                >
                <select 
                    wire:model.live="statusFilter"
                    class="bg-slate-50 border border-slate-100 rounded-2xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                >
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="suspended">{{ __('Suspended') }}</option>
                </select>
            </div>

            @if($affiliates->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    {{ __('No affiliates found.') }}
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase">
                                <th class="pb-3">{{ __('User') }}</th>
                                <th class="pb-3">{{ __('Status') }}</th>
                                <th class="pb-3">{{ __('Balance') }}</th>
                                <th class="pb-3">{{ __('Total Earned') }}</th>
                                <th class="pb-3">{{ __('Bank / Account') }}</th>
                                <th class="pb-3 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                            @foreach($affiliates as $aff)
                                <tr>
                                    <td class="py-4">
                                        <span class="font-extrabold text-slate-900 block">{{ $aff->user->name }}</span>
                                        <span class="text-xs text-slate-400 block">{{ $aff->user->email }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider 
                                            {{ $aff->status === 'active' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                            {{ $aff->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                            {{ $aff->status === 'suspended' ? 'bg-rose-50 text-rose-700' : '' }}
                                        ">
                                            {{ $aff->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 font-bold text-slate-950">₦{{ number_format($aff->balance_ngn, 2) }}</td>
                                    <td class="py-4 text-slate-500">₦{{ number_format($aff->total_earned_ngn, 2) }}</td>
                                    <td class="py-4">
                                        @if($aff->account_number)
                                            <span class="text-xs text-slate-800 font-semibold block">{{ $aff->account_name }}</span>
                                            <span class="text-[10px] text-slate-400 block">{{ $aff->account_number }} (Code: {{ $aff->bank_code }})</span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">{{ __('Not configured') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right space-x-1">
                                        @if($aff->status !== 'active')
                                            <button wire:click="changeStatus({{ $aff->id }}, 'active')" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-black rounded-lg transition-all">
                                                {{ __('Approve') }}
                                            </button>
                                        @endif

                                        @if($aff->status !== 'suspended')
                                            <button wire:click="changeStatus({{ $aff->id }}, 'suspended')" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-black rounded-lg transition-all">
                                                {{ __('Suspend') }}
                                            </button>
                                        @endif

                                        @if($aff->balance_ngn > 0)
                                            <button wire:click="openPayoutModal({{ $aff->id }})" class="px-3 py-1.5 bg-slate-900 text-white hover:bg-slate-800 text-xs font-black rounded-lg transition-all">
                                                {{ __('Pay') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $affiliates->links() }}
                </div>
            @endif
        </div>

        {{-- Record Manual Payout Modal --}}
        @if($showPayoutModal)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-xl">
                    <h3 class="text-lg font-black text-slate-900 mb-2">{{ __('Record Manual Payout') }}</h3>
                    <p class="text-xs text-slate-500 mb-4">{{ __('Record a bank transfer payment reference manually.') }}</p>

                    <form wire:submit.prevent="recordPayout" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Amount (₦)') }}</label>
                            <input 
                                type="number" 
                                wire:model="payoutAmount" 
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                            >
                            @error('payoutAmount') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Paystack/Transfer Reference') }}</label>
                            <input 
                                type="text" 
                                wire:model="paystackReference" 
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                            >
                            @error('paystackReference') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button 
                                type="button" 
                                wire:click="$set('showPayoutModal', false)"
                                class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black rounded-xl transition-all"
                            >
                                {{ __('Cancel') }}
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition-all shadow-md"
                            >
                                {{ __('Record Payout') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>
