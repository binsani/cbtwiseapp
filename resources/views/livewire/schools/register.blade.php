<div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">{{ __('CBTWise for Schools') }}</h1>
            <p class="text-slate-500 mt-2 max-w-xl mx-auto text-sm">
                {{ __('Scaffold a private CBT testing environment for your students. Assign past questions, track detailed performance, and engage parents.') }}
            </p>
        </div>

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-semibold text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Form Column --}}
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm h-fit">
                <h2 class="text-xl font-black text-slate-900 mb-6">{{ __('School Information') }}</h2>

                <form wire:submit.prevent="register" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('School Name') }}</label>
                            <input 
                                type="text" 
                                wire:model.live="name"
                                placeholder="e.g. Grace International Academy"
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                            >
                            @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Portal Subdomain') }}</label>
                            <div class="flex items-center">
                                <input 
                                    type="text" 
                                    wire:model="subdomain"
                                    placeholder="graceacademy"
                                    class="flex-1 bg-slate-50 border border-slate-100 rounded-l-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                                >
                                <span class="bg-slate-100 border border-l-0 border-slate-100 text-slate-500 rounded-r-xl px-3 py-2.5 text-xs font-bold">
                                    .cbtwise.com
                                </span>
                            </div>
                            @error('subdomain') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Contact Email Address') }}</label>
                            <input 
                                type="email" 
                                wire:model="contact_email"
                                placeholder="e.g. admin@graceacademy.sch.ng"
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                            >
                            @error('contact_email') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Contact Phone Number') }}</label>
                            <input 
                                type="text" 
                                wire:model="contact_phone"
                                placeholder="e.g. 08012345678"
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                            >
                            @error('contact_phone') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('State') }}</label>
                            <select 
                                wire:model="state"
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                            >
                                <option value="">{{ __('-- Select State --') }}</option>
                                @php
                                    $states = ['Lagos', 'Abuja (FCT)', 'Oyo', 'Ogun', 'Kano', 'Kaduna', 'Rivers', 'Anambra', 'Edo', 'Delta', 'Enugu', 'Kwara', 'Osun', 'Ondo', 'Imo', 'Abia', 'Akwa Ibom', 'Cross River', 'Plateau', 'Bauchi', 'Sokoto', 'Benue', 'Kogi', 'Nasarawa', 'Niger', 'Adamawa', 'Taraba', 'Borno', 'Yobe', 'Gombe', 'Jigawa', 'Katsina', 'Kebbi', 'Zamfara', 'Bayelsa', 'Ebonyi', 'Ekiti'];
                                    sort($states);
                                @endphp
                                @foreach($states as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('state') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Address') }}</label>
                            <input 
                                type="text" 
                                wire:model="address"
                                placeholder="e.g. 12 School Road, Ikeja"
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:outline-none"
                            >
                            @error('address') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-50">
                        <button 
                            type="submit"
                            class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl shadow-lg transition-all hover:-translate-y-0.5"
                        >
                            🚀 {{ __('Register School & Start Trial') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Plans / Info Column --}}
            <div class="space-y-4">
                <h2 class="text-lg font-black text-slate-900 mb-2 px-1">{{ __('Select Seat Tier') }}</h2>

                <div 
                    wire:click="$set('tier', 'starter')"
                    class="bg-white border-2 rounded-3xl p-5 cursor-pointer transition-all shadow-sm flex items-start gap-4 {{ $tier === 'starter' ? 'border-emerald-600 ring-2 ring-emerald-50' : 'border-slate-100 hover:border-slate-200' }}"
                >
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-black">
                        🌱
                    </div>
                    <div class="flex-1">
                        <span class="font-extrabold text-sm text-slate-900 block">{{ __('Starter') }}</span>
                        <span class="text-xs text-slate-400 block mt-0.5">{{ __('50 seats • 14 days free trial') }}</span>
                        <span class="text-sm font-black text-emerald-600 block mt-2">₦15,000 / month</span>
                    </div>
                </div>

                <div 
                    wire:click="$set('tier', 'growth')"
                    class="bg-white border-2 rounded-3xl p-5 cursor-pointer transition-all shadow-sm flex items-start gap-4 {{ $tier === 'growth' ? 'border-emerald-600 ring-2 ring-emerald-50' : 'border-slate-100 hover:border-slate-200' }}"
                >
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-black">
                        🚀
                    </div>
                    <div class="flex-1">
                        <span class="font-extrabold text-sm text-slate-900 block">{{ __('Growth') }}</span>
                        <span class="text-xs text-slate-400 block mt-0.5">{{ __('200 seats • 14 days free trial') }}</span>
                        <span class="text-sm font-black text-emerald-600 block mt-2">₦45,000 / month</span>
                    </div>
                </div>

                <div 
                    wire:click="$set('tier', 'pro')"
                    class="bg-white border-2 rounded-3xl p-5 cursor-pointer transition-all shadow-sm flex items-start gap-4 {{ $tier === 'pro' ? 'border-emerald-600 ring-2 ring-emerald-50' : 'border-slate-100 hover:border-slate-200' }}"
                >
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-black">
                        ⚡
                    </div>
                    <div class="flex-1">
                        <span class="font-extrabold text-sm text-slate-900 block">{{ __('Pro') }}</span>
                        <span class="text-xs text-slate-400 block mt-0.5">{{ __('500 seats • 14 days free trial') }}</span>
                        <span class="text-sm font-black text-emerald-600 block mt-2">₦100,000 / month</span>
                    </div>
                </div>

                <div 
                    wire:click="$set('tier', 'enterprise')"
                    class="bg-white border-2 rounded-3xl p-5 cursor-pointer transition-all shadow-sm flex items-start gap-4 {{ $tier === 'enterprise' ? 'border-emerald-600 ring-2 ring-emerald-50' : 'border-slate-100 hover:border-slate-200' }}"
                >
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg font-black">
                        🏢
                    </div>
                    <div class="flex-1">
                        <span class="font-extrabold text-sm text-slate-900 block">{{ __('Enterprise') }}</span>
                        <span class="text-xs text-slate-400 block mt-0.5">{{ __('Unlimited seats • Dedicated server') }}</span>
                        <span class="text-sm font-black text-slate-500 block mt-2">{{ __('Custom Billing') }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
