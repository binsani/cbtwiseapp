<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 font-sans space-y-8">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-heading">Notifications</h1>
            <p class="text-slate-500 text-sm mt-1">Stay updated with study alerts, achievements, and upgrades.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                wire:click="markAllAsRead" 
                class="px-4 py-2 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50 transition-colors"
            >
                Mark All Read
            </button>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition-all shadow-sm">
                &larr; Dashboard
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="p-6 bg-white rounded-3xl border border-slate-100/80 shadow-sm flex items-start gap-4 relative overflow-hidden transition-all
                {{ $notification->read_at ? 'opacity-70' : 'border-l-4 border-l-emerald-500' }}">
                
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-50 border border-slate-100">
                    @if($notification->type === 'streak')
                        🔥
                    @elseif($notification->type === 'achievement')
                        🏆
                    @elseif($notification->type === 'success')
                        ✅
                    @elseif($notification->type === 'warning')
                        ⚠️
                    @else
                        📢
                    @endif
                </div>

                <div class="flex-1 space-y-1">
                    <div class="flex justify-between items-start gap-4">
                        <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $notification->title }}</h4>
                        <span class="text-[10px] text-slate-400 font-semibold">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $notification->message }}</p>
                    
                    @if($notification->action_url)
                        <a href="{{ $notification->action_url }}" class="inline-block text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-2">
                            View Details &rarr;
                        </a>
                    @endif
                </div>

                @if(!$notification->read_at)
                    <button 
                        wire:click="markAsRead({{ $notification->id }})" 
                        class="text-xs text-slate-400 hover:text-slate-600 self-center font-bold"
                        title="Mark as read"
                    >
                        Mark Read
                    </button>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <span class="text-3xl block mb-2">🔔</span>
                <p class="text-slate-500 text-sm">Your notifications inbox is completely clear.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
