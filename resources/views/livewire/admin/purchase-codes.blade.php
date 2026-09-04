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
                <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Subscriptions
                </a>
                <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14"/></svg>
                    Analytics
                </a>
                <a href="{{ route('admin.messages') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 00-2-2H6a2 2 0 00-2 2v5m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2"/></svg>
                    Messages
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-600 hover:text-slate-900 font-bold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Reports
                </a>
                <a href="{{ route('admin.purchase-codes') }}" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 font-extrabold rounded-2xl text-sm transition-all">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5-3a2 2 0 00-2 2v7a2 2 0 002 2h5a2 2 0 002-2V9a2 2 0 00-2-2h-5z"/></svg>
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
                <h1 class="text-2xl font-black text-slate-950 font-heading">Purchase Codes</h1>
                <p class="text-xs text-slate-500 mt-0.5">Generate and manage purchase codes for premium access</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button wire:click="downloadCsv" class="px-4 py-2 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5">
                    <span>📥</span> Download as CSV
                </button>
                <button wire:click="openModal" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl text-xs font-bold shadow-sm shadow-emerald-700/10 transition-colors flex items-center gap-1.5">
                    <span>+</span> Generate Code
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-semibold rounded-2xl">
                {{ session('message') }}
            </div>
        @endif

        <!-- Visual Analytics Counter Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-xs font-bold text-slate-400 mb-2">Total Codes</p>
                <h3 class="text-3xl font-black text-slate-950 font-heading leading-tight">{{ $totalCodes }}</h3>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-xs font-bold text-slate-400 mb-2">Active</p>
                <h3 class="text-3xl font-black text-emerald-600 font-heading leading-tight">{{ $activeCodes }}</h3>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-xs font-bold text-slate-400 mb-2">Used</p>
                <h3 class="text-3xl font-black text-blue-600 font-heading leading-tight">{{ $usedCodes }}</h3>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                <p class="text-xs font-bold text-slate-400 mb-2">Cancelled</p>
                <h3 class="text-3xl font-black text-red-600 font-heading leading-tight">{{ $cancelledCodes }}</h3>
            </div>
        </div>

        <!-- Codes Panel -->
        <div class="bg-white border border-slate-100/80 rounded-3xl shadow-sm overflow-hidden p-6 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-950 font-heading">All Purchase Codes</h2>
                <p class="text-xs text-slate-400 mt-0.5">Each code has auto-generated login credentials tied to cbtwise.com.ng</p>
            </div>

            <!-- Search input & status select -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search codes by user, email or string..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors uppercase font-semibold" />
                </div>

                <div class="flex items-center gap-2">
                    <select wire:model.live="filterStatus" class="px-4 py-2.5 border border-slate-200 rounded-2xl text-xs font-bold bg-white text-slate-700 focus:border-emerald-500">
                        <option value="all">All Statuses</option>
                        <option value="active">Active (Unused)</option>
                        <option value="used">Used</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto -mx-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Credentials</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($codes as $c)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">
                                    {{ $c->usedBy->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 space-y-1">
                                    @if ($c->isUsed())
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono text-slate-400 select-all">{{ $c->usedBy->email }}</span>
                                            <button onclick="navigator.clipboard.writeText('{{ $c->usedBy->email }}')" class="text-slate-400 hover:text-slate-700" title="Copy Email">📋</button>
                                        </div>
                                        <div class="flex items-center gap-1.5" x-data="{ show: false }">
                                            <span class="font-mono text-slate-400" x-text="show ? 'password123' : '•••••••••'"></span>
                                            <button @click="show = !show" class="text-slate-400 hover:text-slate-700">👁️</button>
                                        </div>
                                    @else
                                        <span class="text-slate-300 italic">Credentials not generated</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <span class="bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-mono text-slate-700 tracking-wider font-bold select-all">{{ $c->code }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ $c->code }}')" class="text-slate-400 hover:text-slate-700" title="Copy Code">📋</button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-semibold">
                                    {{ $c->plan_duration_days }} days
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($c->isUsed())
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700">used</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700">active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $c->created_at->format('n/j/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                                    —
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    —
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-sm text-slate-400">
                                    No purchase codes found matching criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($codes->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $codes->links() }}
                </div>
            @endif
        </div>

        <!-- Generation Modal -->
        @if ($isModalOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="generate-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100 p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 font-heading" id="generate-modal-title">
                                    Generate Purchase Code
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Enter the student's name — email and password will be auto-generated</p>
                            </div>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="generateBatch" class="space-y-5 mt-6 text-slate-700">
                            <!-- Student Full Name -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Student Full Name</label>
                                <input wire:model="studentName" type="text" placeholder="e.g. John Doe" class="w-full px-4 py-3 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
                                @error('studentName') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Quantity</label>
                                <input wire:model="quantity" type="number" min="1" max="100" class="w-full px-4 py-3 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
                                <span class="text-[10px] text-slate-400 mt-1 block">Maximum 100 codes per batch</span>
                                @error('quantity') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Duration (Days) -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Duration (Days)</label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" wire:click="$set('durationDays', 30)" class="px-4 py-2 border rounded-xl text-xs font-bold transition-all {{ $durationDays == 30 ? 'bg-emerald-800 border-emerald-800 text-white' : 'border-slate-200 text-slate-700 bg-white hover:bg-slate-50' }}">
                                        30 days
                                    </button>
                                    <button type="button" wire:click="$set('durationDays', 90)" class="px-4 py-2 border rounded-xl text-xs font-bold transition-all {{ $durationDays == 90 ? 'bg-emerald-800 border-emerald-800 text-white' : 'border-slate-200 text-slate-700 bg-white hover:bg-slate-50' }}">
                                        90 days
                                    </button>
                                    <button type="button" wire:click="$set('durationDays', 180)" class="px-4 py-2 border rounded-xl text-xs font-bold transition-all {{ $durationDays == 180 ? 'bg-emerald-800 border-emerald-800 text-white' : 'border-slate-200 text-slate-700 bg-white hover:bg-slate-50' }}">
                                        180 days
                                    </button>
                                    <button type="button" wire:click="$set('durationDays', 365)" class="px-4 py-2 border rounded-xl text-xs font-bold transition-all {{ $durationDays == 365 ? 'bg-emerald-800 border-emerald-800 text-white' : 'border-slate-200 text-slate-700 bg-white hover:bg-slate-50' }}">
                                        1 year
                                    </button>
                                </div>
                                <input wire:model="durationDays" type="number" class="w-full px-4 py-3 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
                                <span class="text-[10px] text-slate-400 mt-1 block">Select a preset or enter a custom number of days</span>
                                @error('durationDays') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Notes (Optional) -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Notes (Optional)</label>
                                <input wire:model="notes" type="text" placeholder="e.g. Batch for bank transfer payments" class="w-full px-4 py-3 border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-2xl text-sm transition-colors" />
                                @error('notes') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <button type="button" wire:click="closeModal" class="px-5 py-2.5 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-bold rounded-xl text-xs transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                    Review & Confirm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </main>
</div>
