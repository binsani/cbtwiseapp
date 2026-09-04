@extends('layouts.public')

@section('title', 'Refund Policy — CBTWise')
@section('meta_description', 'Read the CBTWise Refund Policy. Learn about refund windows, voucher code rules, and transaction resolution guidelines.')

@section('content')
<section class="py-20 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-100 shadow-sm space-y-8">
            <div class="border-b border-slate-100 pb-6">
                <h1 class="text-3xl font-black text-slate-900 font-heading">Refund Policy</h1>
                <p class="text-slate-400 text-xs mt-2">Last Updated: June 17, 2026</p>
            </div>

            <article class="prose prose-slate max-w-none text-sm text-slate-600 space-y-6 leading-relaxed">
                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">1. General Principles</h3>
                    <p>
                        At CBTWise, we strive to ensure that all students have an exceptional preparation experience. Since our services consist of digital software access, immediate past-question downloads, and instant AI tutor credits, refunds are subject to specific guidelines to prevent abuse.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">2. Refund Window</h3>
                    <p>
                        We offer a 7-day refund window from the date of purchase under the following conditions:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>You have completed less than 3 exam practice sessions during the 7 days.</li>
                        <li>You have not generated any AI Study Plan reports.</li>
                        <li>You experienced a documented technical failure (e.g., system downtime preventing exam submission) that our support team could not resolve within 48 hours.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">3. Paystack Transactions</h3>
                    <p>
                        All online payments are handled securely through Paystack. Approved refunds will be reversed to the original card or bank account used for payment. Paystack processing fees are non-refundable, and refunds can take 3 to 10 business days to reflect in your bank account depending on banking network speed.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">4. Physical Voucher Codes</h3>
                    <p>
                        Vouchers, scratch cards, and access codes purchased offline from school partners, bookstores, or educational agents are non-refundable. All voucher sales are final. If you encounter a code printing or activation error, please contact support for a replacement.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">5. How to Request a Refund</h3>
                    <p>
                        To request a refund, please send an email to support@cbtwise.com with:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>Your registered email address.</li>
                        <li>The Paystack transaction reference (sent to your email on purchase).</li>
                        <li>A brief explanation of the issue or technical error encountered.</li>
                    </ul>
                </div>

                <div class="pt-6 border-t border-slate-100 text-xs text-slate-400">
                    If you have questions regarding this policy, contact us at legal@cbtwise.com.
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
