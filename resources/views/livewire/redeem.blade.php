<div class="min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8 bg-slate-50"
     x-data="{
         isLoggedIn: {{ Auth::check() ? 'true' : 'false' }},
         recaptchaKey: '{{ env('RECAPTCHA_SITE_KEY') }}',
         submitForm() {
             if (!this.recaptchaKey) {
                 $wire.redeem();
                 return;
             }
             grecaptcha.ready(() => {
                 grecaptcha.execute(this.recaptchaKey, {action: 'redeem'}).then((token) => {
                     $wire.set('recaptchaToken', token);
                     $wire.redeem();
                 });
             });
         }
     }">
    <div class="sm:mx-auto x-max-w-md w-full max-w-md">
        <h2 class="text-center text-3xl font-extrabold text-slate-900 tracking-tight font-heading">
            Redeem Purchase Code
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Enter your CBT-XXXX code to activate your Premium access instantly.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl rounded-3xl border border-slate-100 sm:px-10">
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl text-sm font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl text-sm font-semibold shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Code field -->
                <div>
                    <label for="code" class="block text-sm font-semibold text-slate-700">
                        Purchase Code
                    </label>
                    <div class="mt-1">
                        <input wire:model="code" id="code" name="code" type="text" placeholder="CBT-XXXXXXXX" required
                               class="appearance-none block w-full px-4 py-3 border border-slate-200 rounded-2xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm uppercase tracking-wider font-bold">
                    </div>
                    @error('code')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guest registration fields if not logged in -->
                @guest
                    <div class="border-t border-slate-100 pt-6 space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                            Create Account
                        </h3>
                        <p class="text-xs text-slate-500">
                            Since you are not logged in, we will automatically create an account for you. Your purchase code will be your temporary password.
                        </p>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                            <input wire:model="name" id="name" type="text" required
                                   class="mt-1 block w-full px-4 py-2.5 border border-slate-200 rounded-2xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('name') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                            <input wire:model="email" id="email" type="email" required
                                   class="mt-1 block w-full px-4 py-2.5 border border-slate-200 rounded-2xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('email') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700">Phone Number (Optional)</label>
                            <input wire:model="phone" id="phone" type="text"
                                   class="mt-1 block w-full px-4 py-2.5 border border-slate-200 rounded-2xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('phone') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-slate-700">State</label>
                            <select wire:model="state" id="state" required
                                    class="mt-1 block w-full px-4 py-2.5 border border-slate-200 rounded-2xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                <option value="">Select State</option>
                                @foreach ($states as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                            @error('state') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Exam Year -->
                        <div>
                            <label for="exam_year" class="block text-sm font-medium text-slate-700">Exam Year</label>
                            <select wire:model="exam_year" id="exam_year" required
                                    class="mt-1 block w-full px-4 py-2.5 border border-slate-200 rounded-2xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @foreach (range(date('Y') + 1, 2024) as $yr)
                                    <option value="{{ $yr }}">{{ $yr }}</option>
                                @endforeach
                            </select>
                            @error('exam_year') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endguest

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                        Redeem & Start Learning
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(env('RECAPTCHA_SITE_KEY'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    @endif
</div>
