<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Question Reports</h1>
                <p class="text-xs text-slate-500 mt-0.5">Review student-reported question errors</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Search Bar & Filters -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="relative w-full sm:max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search reports..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
            </div>

            <!-- Pending Badge Filter -->
            <div class="flex items-center gap-2">
                <button wire:click="$set('status', 'open')" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $status === 'open' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $pendingCount }} pending
                </button>
                <button wire:click="$set('status', 'fixed')" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $status === 'fixed' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Resolved
                </button>
                <button wire:click="$set('status', 'dismissed')" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $status === 'dismissed' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Dismissed
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Question Context</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Reporter Details</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reports as $rep)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-800 line-clamp-2">
                                        {{ $rep->question ? strip_tags($rep->question->question_text) : 'Deleted Question' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-700 capitalize">
                                        {{ str_replace('_', ' ', $rep->reason) }}
                                    </div>
                                    @if ($rep->notes)
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $rep->notes }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    <div class="font-bold text-slate-700">{{ $rep->reporter->name ?? 'Candidate' }}</div>
                                    <div class="mt-0.5">{{ $rep->reporter->email ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                    {{ $rep->created_at->format('n/j/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    @if ($rep->status === 'open')
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="resolveReport({{ $rep->id }})" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 transition-colors font-bold">
                                                Resolve
                                            </button>
                                            <button wire:click="dismissReport({{ $rep->id }})" class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors font-bold">
                                                Dismiss
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic font-medium capitalize">{{ $rep->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                    No reports found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
