<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Subscriptions & Passes</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ number_format($totalCount) }} total subscriptions &bull; <span class="text-emerald-600 font-bold">{{ number_format($activeCount) }} active paid accounts</span></p>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="exportCsv" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export CSV</span>
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filter Badges & Search -->
        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by student name or email..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs font-medium transition-colors outline-none" />
                </div>

                <!-- Filter Badges -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('statusFilter', 'all')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'all' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All ({{ $totalCount }})
                    </button>
                    <button wire:click="$set('statusFilter', 'active')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'active' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Active ({{ $activeCount }})
                    </button>
                    <button wire:click="$set('statusFilter', 'expired')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'expired' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Expired ({{ $expiredCount }})
                    </button>
                    <button wire:click="$set('statusFilter', 'cancelled')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'cancelled' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Free Tier ({{ $cancelledCount }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Subscriber</th>
                            <th class="px-6 py-4">Plan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Expires At</th>
                            <th class="px-6 py-4">Transaction / Code</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($records as $rec)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $rec['user']->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $rec['user']->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-800 capitalize">
                                    {{ $rec['user']->plan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeClass = [
                                            'active' => 'bg-emerald-50 text-emerald-700',
                                            'expired' => 'bg-amber-50 text-amber-700',
                                            'free' => 'bg-slate-100 text-slate-500',
                                        ][$rec['status']] ?? 'bg-slate-100 text-slate-500';
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $badgeClass }}">
                                        {{ $rec['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                    {{ $rec['ends'] ? \Carbon\Carbon::parse($rec['ends'])->format('M d, Y') : 'Lifetime / None' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                    {{ $rec['ref'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($rec['status'] === 'active')
                                            <button wire:click="extendSubscription({{ $rec['user']->id }}, 30)" 
                                                    class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors" title="Extend 30 Days">
                                                +30 Days
                                            </button>
                                            <button wire:click="cancelSubscription({{ $rec['user']->id }})" 
                                                    wire:confirm="Revert {{ $rec['user']->name }} to free tier?"
                                                    class="px-2.5 py-1 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors" title="Cancel Subscription">
                                                Cancel
                                            </button>
                                        @else
                                            <button wire:click="manualActivate({{ $rec['user']->id }}, 30)" 
                                                    class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors" title="Activate Premium">
                                                Activate (30d)
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-xs text-slate-400">
                                    No subscriptions registered matching filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </main>
</div>
