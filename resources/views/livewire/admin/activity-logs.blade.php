<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Audit & Activity Logs</h1>
                <p class="text-xs text-slate-500 mt-0.5">Immutable audit trail of administrator actions, logins, and system operations</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-100 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Audit Mode
                </span>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Search action, IP, or resource..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Action Filter -->
                <div>
                    <select wire:model.live="actionFilter" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        <option value="all">All Actions</option>
                        @foreach($distinctActions as $act)
                            <option value="{{ $act }}">{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Admin Filter -->
                <div>
                    <select wire:model.live="adminFilter" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        <option value="all">All Staff & Administrators</option>
                        @foreach($admins as $adm)
                            <option value="{{ $adm->id }}">{{ $adm->name }} ({{ $adm->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-6">Timestamp</th>
                            <th class="py-3 px-6">Administrator</th>
                            <th class="py-3 px-6">Action</th>
                            <th class="py-3 px-6">Resource / Model</th>
                            <th class="py-3 px-6">IP Address</th>
                            <th class="py-3 px-6 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-6 text-slate-500 whitespace-nowrap">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                    <span class="text-[10px] text-slate-400 block">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="py-3.5 px-6 font-bold text-slate-900 whitespace-nowrap">
                                    {{ $log->admin->name ?? 'System' }}
                                    <span class="text-[10px] text-slate-400 block">{{ $log->admin->email ?? '' }}</span>
                                </td>
                                <td class="py-3.5 px-6 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 font-mono text-[11px] text-slate-600 truncate max-w-xs">
                                    {{ class_basename($log->subject_type ?? 'N/A') }}
                                    @if($log->subject_id)
                                        <span class="text-slate-400">#{{ $log->subject_id }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 font-mono text-[11px] text-slate-500 whitespace-nowrap">
                                    {{ $log->ip_address ?? '—' }}
                                </td>
                                <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                    <button wire:click="viewDetails({{ $log->id }})" 
                                            class="px-2.5 py-1 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors">
                                        Inspect
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                                    No administrative activity logs recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        <!-- Detail Inspection Modal -->
        @if($viewingLog)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-4 max-h-[85vh] overflow-y-auto">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-black text-slate-900 font-heading">Log Event #{{ $viewingLog->id }}</h3>
                        <button wire:click="closeDetails" class="text-slate-400 hover:text-slate-600 text-sm font-bold">✕</button>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Action</span>
                            <p class="font-bold text-slate-800">{{ $viewingLog->action }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Administrator</span>
                            <p class="font-bold text-slate-800">{{ $viewingLog->admin->name ?? 'System' }} ({{ $viewingLog->admin->email ?? 'N/A' }})</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Resource Target</span>
                            <p class="font-mono text-slate-700">{{ $viewingLog->subject_type ?? 'None' }} [ID: {{ $viewingLog->subject_id ?? 'N/A' }}]</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Network Client</span>
                            <p class="font-mono text-slate-700">{{ $viewingLog->ip_address }} &bull; {{ $viewingLog->user_agent ?? 'Unknown Agent' }}</p>
                        </div>
                        @if($viewingLog->meta)
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Metadata Context</span>
                                <pre class="p-3 bg-slate-900 text-emerald-400 rounded-xl font-mono text-[11px] overflow-x-auto mt-1">{{ json_encode($viewingLog->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif
                        @if($viewingLog->old_values)
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Before State (Old Values)</span>
                                <pre class="p-3 bg-slate-900 text-rose-300 rounded-xl font-mono text-[11px] overflow-x-auto mt-1">{{ json_encode($viewingLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif
                        @if($viewingLog->new_values)
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">After State (New Values)</span>
                                <pre class="p-3 bg-slate-900 text-emerald-300 rounded-xl font-mono text-[11px] overflow-x-auto mt-1">{{ json_encode($viewingLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button wire:click="closeDetails" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </main>
</div>
