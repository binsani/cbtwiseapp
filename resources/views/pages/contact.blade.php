@extends('layouts.public')

@section('title', 'Contact Us — CBTWise Help & Support')
@section('meta_description', 'Get in touch with the CBTWise academic or technical support team. Submit a message or find our email details.')

@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact CBTWise",
  "description": "Get support or send feedback to CBTWise."
}
</script>
@endsection

@section('content')
<section class="py-20 bg-gradient-to-b from-emerald-50/20 via-white to-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex justify-center mb-6 text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-slate-400 md:ml-2">Contact</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="text-center max-w-2xl mx-auto mb-16">
            <h1 class="text-4xl font-black text-slate-950 font-heading tracking-tight">
                Get In <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Touch</span>
            </h1>
            <p class="mt-4 text-slate-600">
                Have questions or feedback? Send us a message and we'll reply as soon as possible.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-stretch">
            <!-- Form Block -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-100 text-sm font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 text-red-800 border border-red-100 text-sm font-medium">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Honeypot -->
                    <input type="text" name="honeypot" class="hidden" tabindex="-1" autocomplete="off">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Your Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm"
                                placeholder="Enter your full name"
                            >
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm"
                                placeholder="name@domain.com"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-bold text-slate-700 mb-2">Subject</label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            value="{{ old('subject') }}" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm"
                            placeholder="How can we help you?"
                        >
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-bold text-slate-700 mb-2">Message</label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm resize-none"
                            placeholder="Type your message details here..."
                        >{{ old('message') }}</textarea>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl text-center shadow-lg shadow-emerald-600/10 transition-colors"
                    >
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Sidebar Info -->
            <div class="bg-slate-900 text-white rounded-3xl p-8 shadow-xl flex flex-col justify-between">
                <div class="space-y-6">
                    <h3 class="text-xl font-bold font-heading">Support Information</h3>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Need assistance with a payment, access code verification, or curriculum suggestions? Reach out directly.
                    </p>

                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <span class="text-emerald-400 font-bold">📧</span>
                            <div>
                                <h4 class="font-bold">Email Support</h4>
                                <p class="text-slate-400 mt-1">support@cbtwise.com</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-emerald-400 font-bold">🕒</span>
                            <div>
                                <h4 class="font-bold">Response Time</h4>
                                <p class="text-slate-400 mt-1">Within 24 Hours (Mon - Sat)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 text-xs text-slate-400 leading-relaxed">
                    Registered Office:<br>
                    CBTWise EdTech Solutions Ltd,<br>
                    Yaba, Lagos, Nigeria.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
