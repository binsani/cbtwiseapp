<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Contact Messages</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ number_format($totalMessages) }} messages received from public contact form</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Search Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="relative w-full sm:max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, email or message..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/5">Sender</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/2">Message Preview</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-28">Received</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($messages as $msg)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-800">{{ $msg->name }}</div>
                                    <a href="mailto:{{ $msg->email }}" class="text-xs text-emerald-600 hover:underline mt-0.5 block">{{ $msg->email }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 cursor-pointer" wire:click="viewMessage({{ $msg->id }})">
                                    <p class="line-clamp-2 text-slate-700 hover:text-emerald-700 transition-colors">
                                        {{ $msg->message }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                    {{ $msg->created_at->format('M d, Y') }}
                                    <div class="text-[10px] text-slate-300">{{ $msg->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="viewMessage({{ $msg->id }})" class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Read Full Message">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <a href="mailto:{{ $msg->email }}?subject={{ rawurlencode('Re: Your inquiry on CBTWise') }}&body={{ rawurlencode("Hi {$msg->name},\n\nThank you for reaching out to CBTWise.\n\n") }}" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Reply via Email">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </a>
                                        <button wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Are you sure you want to delete this message?" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Message">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-sm text-slate-400">No messages found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-slate-100 bg-slate-50/30 flex justify-between items-center text-xs text-slate-500">
                <span>Showing {{ count($messages) }} of {{ $totalMessages }} messages</span>
                @if ($messages->hasPages())
                    {{ $messages->links() }}
                @endif
            </div>
        </div>

        <!-- View Message Modal -->
        @if ($isViewModalOpen && $selectedMessage)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="view-message-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeViewModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                        <div class="bg-white p-6 sm:p-8">
                            <div class="flex justify-between items-start pb-4 border-b border-slate-100">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-950 font-heading" id="view-message-title">
                                        {{ $selectedMessage->name }}
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        <a href="mailto:{{ $selectedMessage->email }}" class="text-emerald-600 hover:underline">{{ $selectedMessage->email }}</a> &bull; {{ $selectedMessage->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                                <button wire:click="closeViewModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="mt-6">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Message Body</label>
                                <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 text-sm whitespace-pre-wrap leading-relaxed max-h-80 overflow-y-auto font-sans">
                                    {{ $selectedMessage->message }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3 pt-6 mt-6 border-t border-slate-100">
                                <button wire:click="deleteMessage({{ $selectedMessage->id }})" wire:confirm="Are you sure you want to delete this message?" class="px-4 py-2 border border-red-200 text-red-600 hover:bg-red-50 font-bold rounded-xl text-xs transition-colors">
                                    Delete Message
                                </button>

                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="closeViewModal" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                        Close
                                    </button>
                                    <a href="mailto:{{ $selectedMessage->email }}?subject={{ rawurlencode('Re: Your inquiry on CBTWise') }}&body={{ rawurlencode("Hi {$selectedMessage->name},\n\nThank you for contacting CBTWise.\n\n") }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Reply via Email
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
</div>
