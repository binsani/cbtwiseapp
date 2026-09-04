<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <aside class="w-full lg:w-64 bg-white border-r border-slate-100 flex-shrink-0 p-6 space-y-8 font-sans">
        <div>
            <p class="text-xs font-black uppercase tracking-wider text-slate-400 mb-4">Admin Panel</p>
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.questions') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Questions
                </a>
                <a href="{{ route('admin.exams-subjects') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Exams & Subjects
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 font-extrabold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Subscriptions
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14"/></svg>
                    Analytics
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2"/></svg>
                    Messages
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Reports
                </a>
                <a href="{{ route('admin.purchase-codes') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5-3a2 2 0 00-2 2v7a2 2 0 002 2h5a2 2 0 002-2V9a2 2 0 00-2-2h-5z"/></svg>
                    Purchase Codes
                </a>
            </nav>
        </div>

        <div class="border-t border-slate-100 pt-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to App
            </a>
        </div>
    </aside>

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
