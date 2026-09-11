<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Contact Messages</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ number_format($totalMessages) }} total messages &bull; <span class="text-emerald-600 font-bold">{{ number_format($newMessagesCount) }} unread</span></p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, email or message..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs font-medium transition-colors outline-none" />
                </div>

                <!-- Status Filter Badges -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('statusFilter', 'all')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'all' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All ({{ $totalMessages }})
                    </button>
                    <button wire:click="$set('statusFilter', 'new')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'new' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        New Unread ({{ $newMessagesCount }})
                    </button>
                    <button wire:click="$set('statusFilter', 'read')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'read' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Read
                    </button>
                    <button wire:click="$set('statusFilter', 'replied')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'replied' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Replied
                    </button>
                    <button wire:click="$set('statusFilter', 'spam')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'spam' ? 'bg-rose-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Spam
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
                            <th class="px-6 py-4">Sender</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Message Preview</th>
                            <th class="px-6 py-4">Received</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($messages as $msg)
                            <tr class="hover:bg-slate-50/40 transition-colors {{ $msg->status === 'new' ? 'font-semibold bg-emerald-50/20' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $msg->name }}</div>
                                    <a href="mailto:{{ $msg->email }}" class="text-[11px] text-emerald-600 hover:underline mt-0.5 block font-mono">{{ $msg->email }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeStyle = match($msg->status) {
                                            'new' => 'bg-emerald-100 text-emerald-800 animate-pulse',
                                            'replied' => 'bg-blue-100 text-blue-800',
                                            'spam' => 'bg-rose-100 text-rose-800',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $badgeStyle }}">
                                        {{ $msg->status ?? 'new' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 cursor-pointer" wire:click="viewMessage({{ $msg->id }})">
                                    <p class="line-clamp-2 text-slate-700 hover:text-emerald-700 transition-colors">
                                        {{ $msg->message }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-slate-400 whitespace-nowrap">
                                    {{ $msg->created_at->format('M d, Y') }}
                                    <div class="text-[10px] text-slate-300">{{ $msg->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button wire:click="viewMessage({{ $msg->id }})" class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Read & Reply">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <button wire:click="markAsSpam({{ $msg->id }})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Mark as Spam">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        </button>
                                        <button wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Delete this message?" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Message">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-xs text-slate-400">
                                    No contact messages found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($messages->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>

        <!-- View & Reply Message Modal -->
        @if ($isViewModalOpen && $selectedMessage)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-start pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-black text-slate-900 font-heading">
                                {{ $selectedMessage->name }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                <a href="mailto:{{ $selectedMessage->email }}" class="text-emerald-600 hover:underline font-mono">{{ $selectedMessage->email }}</a> &bull; {{ $selectedMessage->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <button wire:click="closeViewModal" class="text-slate-400 hover:text-slate-600 text-sm font-bold">✕</button>
                    </div>

                    <!-- Message Text -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Message Content</label>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 text-xs whitespace-pre-wrap leading-relaxed">
                            {{ $selectedMessage->message }}
                        </div>
                    </div>

                    <!-- Internal Note -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Internal Staff Note</label>
                        <input type="text" wire:model="internalNotes" placeholder="e.g. Student reported billing issue, forwarded to payment team" class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <!-- Staff Reply Area -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Record Staff Response</label>
                        <textarea wire:model="replyText" rows="4" placeholder="Type response sent or draft response..." class="w-full p-3.5 text-xs bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none"></textarea>
                        @error('replyText') <span class="text-[11px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                        <button wire:click="deleteMessage({{ $selectedMessage->id }})" wire:confirm="Delete this message permanently?" class="px-3.5 py-2 text-rose-600 hover:bg-rose-50 font-bold rounded-xl text-xs transition-colors">
                            Delete
                        </button>

                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="closeViewModal" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                Close
                            </button>
                            <button type="button" wire:click="sendReply" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                Save Reply & Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </main>
</div>
