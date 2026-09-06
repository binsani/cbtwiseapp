<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Bulk Question Seeder</h1>
                <p class="text-xs text-slate-500 mt-0.5">Fetch questions from ALOC API for all subjects and cache them locally</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Card Container -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm p-6 max-w-xl">
            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-800">Batches per subject</label>
                
                <div class="flex items-center gap-4">
                    <div class="relative flex-1">
                        <select wire:model="batches" class="w-full px-4 py-3 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors font-bold text-slate-800">
                            <option value="1">1 batches (~40 questions/subject)</option>
                            <option value="2">2 batches (~80 questions/subject)</option>
                            <option value="3">3 batches (~120 questions/subject)</option>
                            <option value="5">5 batches (~200 questions/subject)</option>
                            <option value="7">7 batches (~280 questions/subject)</option>
                            <option value="10">10 batches (~400 questions/subject)</option>
                        </select>
                    </div>

                    <button wire:click="startBulkFetch" wire:loading.attr="disabled" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-2xl text-sm shadow-sm transition-all flex items-center gap-2">
                        <span wire:loading.remove>📥 Start Bulk Fetch</span>
                        <span wire:loading class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                        <span wire:loading>Fetching...</span>
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>
