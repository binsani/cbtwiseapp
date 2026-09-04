@extends('layouts.public')

@section('title', 'Frequently Asked Questions (FAQ) — CBTWise')
@section('meta_description', 'Have questions about CBTWise? Learn how to start practicing, redeem access codes, upgrade your account, and optimize your exam preparations.')

@section('json_ld')
@php
    // Fallback FAQ array for JSON-LD and page display
    $fallbackFaqs = [
        [
            'question' => 'What is CBTWise?',
            'answer' => 'CBTWise is an advanced computer-based test simulator built to help Nigerian students prepare for exams like JAMB UTME, WAEC SSCE, and NECO SSCE. It offers exact exam matches, AI tutoring, and deep performance analytics.'
        ],
        [
            'question' => 'How can I redeem an access code?',
            'answer' => 'If you purchased a voucher or access code offline or from an agent, click the "Redeem Code" link in the header. Enter the code and your email to activate your account instantly.'
        ],
        [
            'question' => 'Are these real JAMB and WAEC past questions?',
            'answer' => 'Yes, CBTWise uses a database of verified official past questions. Our questions are curated and categorized by subjects and topics to align with official syllabi.'
        ],
        [
            'question' => 'How does the AI Tutor work?',
            'answer' => 'When practicing, you can ask the AI Tutor to explain any question. The AI explains why the correct option is right, why other options are wrong, and provides a customized 7-day revision plan.'
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept all secure online payments via Paystack, including credit/debit cards, bank transfers, USSD, and mobile money.'
        ],
        [
            'question' => 'Can I use CBTWise on my mobile phone?',
            'answer' => 'Yes, CBTWise is fully responsive and optimized for all mobile phones, tablets, and computers. You can also install it as a PWA (Progressive Web App) to practice on the go.'
        ],
        [
            'question' => 'Is there a free trial?',
            'answer' => 'Yes, any registered user gets a free daily quota to practice a set number of questions. To unlock unlimited practice runs, you can upgrade to a premium plan.'
        ],
        [
            'question' => 'How do I contact support?',
            'answer' => 'You can visit our Contact page and fill out the support form, or send an email directly to support@cbtwise.com. We respond to all queries within 24 hours.'
        ]
    ];
    $activeFaqs = $faqs->isNotEmpty() ? $faqs : collect($fallbackFaqs)->map(fn($item) => (object)$item);
@endphp

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($activeFaqs as $faq)
    {
      "@type": "Question",
      "name": "{{ $faq->question }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ strip_tags($faq->answer) }}"
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endsection

@section('content')
<section class="py-20 bg-gradient-to-b from-emerald-50/20 via-white to-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <span class="ml-1 text-slate-400 md:ml-2">FAQ</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="text-center max-w-2xl mx-auto mb-16">
            <h1 class="text-4xl font-black text-slate-950 font-heading tracking-tight">
                Frequently Asked <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Questions</span>
            </h1>
            <p class="mt-4 text-slate-600">
                Everything you need to know about using CBTWise to maximize your test scores.
            </p>
        </div>

        <!-- Accordions -->
        <div class="space-y-4" x-data="{ activeIndex: null }">
            @foreach($activeFaqs as $index => $faq)
                <div class="bg-white rounded-3xl border border-slate-100/80 shadow-sm overflow-hidden transition-all duration-300">
                    <button 
                        class="w-full flex items-center justify-between p-6 text-left font-bold text-slate-900 font-heading text-lg focus:outline-none"
                        @click="activeIndex = activeIndex === {{ $index }} ? null : {{ $index }}"
                    >
                        <span>{{ $faq->question }}</span>
                        <svg 
                            class="w-5 h-5 text-slate-400 transition-transform duration-300"
                            :class="activeIndex === {{ $index }} ? 'rotate-180 text-emerald-600' : ''"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div 
                        class="transition-all duration-300 max-h-0 overflow-hidden"
                        x-ref="container{{ $index }}"
                        :style="activeIndex === {{ $index }} ? 'max-height: ' + $refs.container{{ $index }}.scrollHeight + 'px' : ''"
                    >
                        <div class="p-6 pt-0 border-t border-slate-50 text-slate-600 text-sm leading-relaxed">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
