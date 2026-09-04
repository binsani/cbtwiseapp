<div class="max-w-6xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight font-heading sm:text-5xl">
            Choose the Perfect Plan for <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-blue-600">Your Success</span>
        </h1>
        <p class="mt-4 text-xl text-gray-600">Get unlimited access to advanced mock engines, personalized AI tutoring, and in-depth weak-topic study plans.</p>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('error'))
        <div class="max-w-4xl mx-auto mb-8 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl text-sm font-semibold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-5xl mx-auto">
        <!-- Monthly Plan -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-8 flex flex-col justify-between hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 font-heading">Monthly Plan</h3>
                <p class="text-sm text-gray-500 mt-2">Perfect for quick revisions or trying out our premium study tools.</p>
                <div class="mt-6 flex items-baseline">
                    <span class="text-4xl font-black text-gray-900 font-heading">&#8358;1,500</span>
                    <span class="text-gray-500 text-sm ml-2">/ month</span>
                </div>
                
                <ul class="mt-8 space-y-4 text-sm text-gray-600">
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Access all Mock Exams</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>AI Tutor Explanations</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Weak Topic Study Plans</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Full Analytics & Streak tracking</span>
                    </li>
                </ul>
            </div>

            <div class="mt-10">
                <form action="{{ route('payment.initialize') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_type" value="monthly">
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl shadow transition-colors">
                        Upgrade Now
                    </button>
                </form>
            </div>
        </div>

        <!-- Quarterly Plan (Popular) -->
        <div class="relative bg-gradient-to-b from-slate-900 to-slate-950 rounded-3xl p-8 flex flex-col justify-between text-white shadow-2xl hover:-translate-y-1 transition-all duration-300 ring-4 ring-emerald-500 ring-opacity-50">
            <!-- Popular Badge -->
            <span class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-xs font-black uppercase tracking-widest rounded-full shadow-lg">
                MOST POPULAR
            </span>

            <div>
                <h3 class="text-2xl font-bold font-heading text-emerald-400">Quarterly Plan</h3>
                <p class="text-sm text-slate-300 mt-2">Optimal duration for students preparing for the UTME or SSCE exam cycle.</p>
                <div class="mt-6 flex items-baseline">
                    <span class="text-4xl font-black font-heading">&#8358;4,000</span>
                    <span class="text-slate-400 text-sm ml-2">/ 3 months</span>
                </div>
                
                <ul class="mt-8 space-y-4 text-sm text-slate-200">
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Access all Mock Exams</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>AI Tutor Explanations</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Weak Topic Study Plans</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Full Analytics & Streak tracking</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-semibold text-emerald-300">Save 11% compared to monthly</span>
                    </li>
                </ul>
            </div>

            <div class="mt-10">
                <form action="{{ route('payment.initialize') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_type" value="quarterly">
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-extrabold rounded-2xl shadow-lg transition-all">
                        Upgrade Now
                    </button>
                </form>
            </div>
        </div>

        <!-- Yearly Plan -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-8 flex flex-col justify-between hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 font-heading">Yearly Plan</h3>
                <p class="text-sm text-gray-500 mt-2">Best for educators, schools, or long-term learners preparing for multiple cycles.</p>
                <div class="mt-6 flex items-baseline">
                    <span class="text-4xl font-black text-gray-900 font-heading">&#8358;12,000</span>
                    <span class="text-gray-500 text-sm ml-2">/ year</span>
                </div>
                
                <ul class="mt-8 space-y-4 text-sm text-gray-600">
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Access all Mock Exams</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>AI Tutor Explanations</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Weak Topic Study Plans</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Full Analytics & Streak tracking</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-semibold text-emerald-600">Save 33% compared to monthly</span>
                    </li>
                </ul>
            </div>

            <div class="mt-10">
                <form action="{{ route('payment.initialize') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_type" value="yearly">
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl shadow transition-colors">
                        Upgrade Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
