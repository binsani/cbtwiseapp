<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-8 space-y-8 overflow-x-hidden font-sans">
        
        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl text-xs font-bold shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('message') }}</span>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-950 font-heading">Platform Settings</h1>
                <p class="text-xs text-slate-500 mt-0.5">Configure CBTWise system limits, defaults, notifications, and security credentials</p>
            </div>
            <div>
                <button wire:click="saveSettings" 
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-extrabold shadow-sm shadow-emerald-600/20 transition-all flex items-center gap-2">
                    <span wire:loading.remove>Save All Settings</span>
                    <span wire:loading class="flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Saving...
                    </span>
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-200 gap-2 overflow-x-auto">
            <button wire:click="$set('activeTab', 'general')"
                    class="px-4 py-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'general' ? 'border-emerald-600 text-emerald-700 bg-white/60 rounded-t-xl' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                🌐 General & Branding
            </button>
            <button wire:click="$set('activeTab', 'exams')"
                    class="px-4 py-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'exams' ? 'border-emerald-600 text-emerald-700 bg-white/60 rounded-t-xl' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                📝 Exams & Limits
            </button>
            <button wire:click="$set('activeTab', 'questions')"
                    class="px-4 py-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'questions' ? 'border-emerald-600 text-emerald-700 bg-white/60 rounded-t-xl' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                ❓ Question Bank Rules
            </button>
            <button wire:click="$set('activeTab', 'notifications')"
                    class="px-4 py-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'notifications' ? 'border-emerald-600 text-emerald-700 bg-white/60 rounded-t-xl' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                🔔 Notifications & Alerts
            </button>
            <button wire:click="$set('activeTab', 'payment')"
                    class="px-4 py-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'payment' ? 'border-emerald-600 text-emerald-700 bg-white/60 rounded-t-xl' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                💳 Payment Gateway
            </button>
        </div>

        <!-- Settings Cards Container -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm">
            
            <!-- TAB 1: GENERAL -->
            @if($activeTab === 'general')
                <div class="space-y-6 max-w-2xl">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">General Application Preferences</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Platform Name</label>
                            <input type="text" wire:model="app_name" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('app_name') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Support Email</label>
                            <input type="email" wire:model="support_email" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('support_email') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Contact Hotline</label>
                            <input type="text" wire:model="contact_phone" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Default Currency</label>
                            <select wire:model="default_currency" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="NGN">Nigerian Naira (NGN)</option>
                                <option value="USD">US Dollar (USD)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Maintenance Mode</h4>
                            <p class="text-[11px] text-slate-400">Temporarily prevent students from starting new exam simulations</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="maintenance_mode" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            @endif

            <!-- TAB 2: EXAMS -->
            @if($activeTab === 'exams')
                <div class="space-y-6 max-w-2xl">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Exam Sessions & Daily Limits</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Free Tier Daily Limit (Questions)</label>
                            <input type="number" wire:model="free_daily_limit" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <p class="text-[10px] text-slate-400">Resets automatically at midnight African/Lagos time</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Default Exam Duration (Minutes)</label>
                            <input type="number" wire:model="default_duration_minutes" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Passing Score (%)</label>
                            <input type="number" wire:model="default_passing_score" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Practice Session Max Questions</label>
                            <input type="number" wire:model="max_practice_questions" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Negative Marking</h4>
                            <p class="text-[11px] text-slate-400">Deduct 0.25 points for each incorrect answer in simulation mode</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="negative_marking" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            @endif

            <!-- TAB 3: QUESTIONS -->
            @if($activeTab === 'questions')
                <div class="space-y-6 max-w-2xl">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Question Bank & Deduplication</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Duplicate Check Key Length (Chars)</label>
                            <input type="number" wire:model="dedupe_char_length" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <p class="text-[10px] text-slate-400">First N normalized characters used for SHA256 dedupe hash</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Auto-Flag Report Threshold</label>
                            <input type="number" wire:model="auto_flag_reports_threshold" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <p class="text-[10px] text-slate-400">Number of student reports before question is flagged automatically</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Default Question Difficulty</label>
                            <select wire:model="default_difficulty" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Require Explanations</h4>
                            <p class="text-[11px] text-slate-400">Disallow saving questions without full step-by-step working/explanation</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="require_explanation" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            @endif

            <!-- TAB 4: NOTIFICATIONS -->
            @if($activeTab === 'notifications')
                <div class="space-y-6 max-w-2xl">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Administrator Notification Preferences</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Browser Audio Alerts</h4>
                                <p class="text-[11px] text-slate-400">Play subtle chime when real-time student reports or messages arrive</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="browser_sound_alerts" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Question Report Alerts</h4>
                                <p class="text-[11px] text-slate-400">Receive administrative notification for newly submitted question error reports</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="report_alerts" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Payment & Subscription Alerts</h4>
                                <p class="text-[11px] text-slate-400">Notify admin panel whenever a Paystack subscription payment completes</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="payment_alerts" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 5: PAYMENT GATEWAY -->
            @if($activeTab === 'payment')
                <div class="space-y-6 max-w-2xl">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Paystack Integration Credentials</h3>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Paystack Public Key</label>
                            <input type="text" wire:model="paystack_public_key" placeholder="pk_live_xxxx or pk_test_xxxx" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none font-mono">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Paystack Secret Key (Encrypted at Rest)</label>
                            <input type="password" wire:model="paystack_secret_key" placeholder="sk_live_xxxx or sk_test_xxxx" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none font-mono">
                            <p class="text-[10px] text-slate-400">Stored in encrypted format using AES-256-CBC</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Minimum Transaction Amount (NGN)</label>
                            <input type="number" wire:model="minimum_payment_amount" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </main>
</div>
