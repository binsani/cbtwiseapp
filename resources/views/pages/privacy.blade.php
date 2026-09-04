@extends('layouts.public')

@section('title', 'Privacy Policy (NDPR) — CBTWise')
@section('meta_description', 'Read the NDPR-compliant Privacy Policy of CBTWise. Learn what data we collect, how we secure it, and your rights as a Nigerian student.')

@section('content')
<section class="py-20 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-100 shadow-sm space-y-8">
            <div class="border-b border-slate-100 pb-6">
                <h1 class="text-3xl font-black text-slate-900 font-heading">Privacy Policy</h1>
                <p class="text-slate-400 text-xs mt-2">Last Updated: June 17, 2026</p>
            </div>

            <article class="prose prose-slate max-w-none text-sm text-slate-600 space-y-6 leading-relaxed">
                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-emerald-800 text-xs font-semibold">
                    🛡️ This policy is designed to comply with the Nigeria Data Protection Regulation (NDPR) and general data security best practices.
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">1. Data Controller</h3>
                    <p>
                        CBTWise EdTech Solutions Ltd acts as the Data Controller for your personal information. If you wish to query, modify, or delete your user data, please contact our Data Protection Officer (DPO) at privacy@cbtwise.com.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">2. Personal Data We Collect</h3>
                    <p>
                        We collect information necessary to deliver and secure our learning portal:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li><strong>Account Details:</strong> Full name, email address, password, school name, and preferred examination target.</li>
                        <li><strong>Performance Logs:</strong> Your simulator test responses, duration logs, and analytical metrics per subject.</li>
                        <li><strong>Payment Logs:</strong> Transaction identifiers and payment status (processed securely through Paystack; we do not store raw card numbers).</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">3. How We Use Your Data</h3>
                    <p>
                        We process your data to:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>Initialize and manage your practice sessions.</li>
                        <li>Provide personalized dashboard reports, subject weaknesses, and AI tutor explanations.</li>
                        <li>Verify voucher redemptions and premium subscription access.</li>
                        <li>Send system notifications, receipts, and study milestone reminders.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">4. Data Sharing & Security</h3>
                    <p>
                        We do not sell, rent, or lease your personal data to third parties. We utilize industry-standard TLS encryption, firewalls, and secure database protocols. Only essential service providers (e.g., Paystack for billing) have restricted API access.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">5. Your Data Rights under NDPR</h3>
                    <p>
                        As a Nigerian data subject, you hold the following rights:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li><strong>Right to Rectification:</strong> Request corrections to incorrect profile data.</li>
                        <li><strong>Right to Access:</strong> Request a copy of your stored records.</li>
                        <li><strong>Right to Erasure (To be Forgotten):</strong> Request the deletion of your account (we support 7-day grace period soft deletion).</li>
                        <li><strong>Right to Portability:</strong> Export your exam statistics report.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2 font-heading">6. Cookies</h3>
                    <p>
                        We use cookies to keep you logged in and preserve browser preferences. You can disable cookies in browser options, but some portions of the simulator may not function properly.
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-100 text-xs text-slate-400">
                    Questions or complaints regarding this policy should be addressed to the DPO at privacy@cbtwise.com.
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
