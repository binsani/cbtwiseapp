<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Subscriptions</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ $totalCount }} total subscriptions</p>
            </div>
        </div>

        <!-- Search Bar & Filters -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="relative w-full sm:max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by user ID or plan..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
            </div>

            <!-- Filter Badges -->
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('statusFilter', 'all')" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'all' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All ({{ $totalCount }})
                </button>
                <button wire:click="$set('statusFilter', 'active')" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'active' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Active ({{ $activeCount }})
                </button>
                <button wire:click="$set('statusFilter', 'expired')" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'expired' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Expired ({{ $expiredCount }})
                </button>
                <button wire:click="$set('statusFilter', 'cancelled')" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'cancelled' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Cancelled ({{ $cancelledCount }})
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User ID</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Starts</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Ends</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Payment Ref</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $rec)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-mono">
                                    {{ Str::limit($rec['user_id'], 16, '...') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">
                                    {{ $rec['plan'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeClass = [
                                            'active' => 'bg-emerald-50 text-emerald-700',
                                            'expired' => 'bg-amber-50 text-amber-700',
                                            'cancelled' => 'bg-slate-100 text-slate-500',
                                        ][$rec['status']] ?? 'bg-slate-100 text-slate-500';
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $badgeClass }}">
                                        {{ $rec['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $rec['starts'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $rec['ends'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-mono">
                                    {{ $rec['ref'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                    No subscriptions registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-slate-100 bg-slate-50/30 flex justify-between items-center">
                <span class="text-xs text-slate-500 font-medium">Showing {{ count($records) }} of {{ $totalCount }} subscriptions</span>
                @if ($paginator->hasPages())
                    {{ $paginator->links() }}
                @endif
            </div>
        </div>
    </main>
</div>
