<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\AffiliateClick;
use App\Models\AffiliateConversion;
use App\Models\Affiliate;
use App\Mail\ReceiptMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    /**
     * Mark a payment as successful, upgrade user, and record affiliate conversion if applicable.
     */
    public static function processSuccess(Payment $payment, array $paystackData): void
    {
        if ($payment->status !== 'pending') {
            return;
        }

        // 1. Update Payment status
        $payment->update([
            'status' => 'success',
            'paid_at' => now(),
            'paystack_data' => $paystackData,
        ]);

        // 2. Upgrade User
        $user = User::find($payment->user_id);
        if ($user) {
            $currentExpiry = $user->premium_expires_at;
            $baseDate = ($currentExpiry && $currentExpiry->isFuture()) ? $currentExpiry : now();
            
            $user->update([
                'plan' => 'premium',
                'premium_expires_at' => $baseDate->addDays($payment->plan_duration_days),
            ]);

            // Send receipt email
            try {
                Mail::to($user->email)->send(new ReceiptMail($payment));
            } catch (\Exception $e) {
                Log::error('Failed to send Receipt Mail in PaymentService: ' . $e->getMessage());
            }

            // 3. Process Affiliate Conversion
            $metadata = $paystackData['metadata'] ?? [];
            $cookieToken = $metadata['affiliate_token'] ?? null;

            if ($cookieToken) {
                try {
                    $click = AffiliateClick::where('cookie_token', $cookieToken)->latest()->first();
                    
                    if ($click) {
                        $affiliate = Affiliate::find($click->affiliate_id);
                        
                        if ($affiliate && $affiliate->isActive() && $affiliate->user_id !== $user->id) {
                            $rate = (int) config('cbtwise_phase5.affiliate_commission_rate', 20);
                            $commission = ($payment->amountNaira()) * ($rate / 100);

                            AffiliateConversion::create([
                                'affiliate_id'     => $affiliate->id,
                                'referred_user_id' => $user->id,
                                'payment_id'       => $payment->id,
                                'commission_ngn'   => $commission,
                                'commission_rate'  => $rate,
                                'status'           => 'pending',
                                'cookie_token'     => $cookieToken,
                                'converted_at'     => now(),
                            ]);

                            Log::info("Affiliate conversion recorded for affiliate #{$affiliate->id}, referred user #{$user->id}");
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to record affiliate conversion: ' . $e->getMessage());
                }
            }
        }
    }
}
