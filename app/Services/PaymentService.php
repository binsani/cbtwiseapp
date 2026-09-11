<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\AffiliateClick;
use App\Models\AffiliateConversion;
use App\Models\Affiliate;
use App\Mail\ReceiptMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    /**
     * Mark a payment as successful, upgrade user, and record affiliate conversion if applicable.
     */
    public static function processSuccess(Payment $payment, array $paystackData): void
    {
        DB::transaction(function () use ($payment, $paystackData) {
            /** @var Payment|null $lockedPayment */
            $lockedPayment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedPayment || $lockedPayment->status !== 'pending') {
                return;
            }

            // 1. Update Payment status
            $lockedPayment->update([
                'status' => 'success',
                'paid_at' => now(),
                'paystack_data' => $paystackData,
            ]);

            // 2. Upgrade User atomically
            /** @var User|null $user */
            $user = User::where('id', $lockedPayment->user_id)
                ->lockForUpdate()
                ->first();

            if ($user) {
                $currentExpiry = $user->premium_expires_at;
                $baseDate = ($currentExpiry && $currentExpiry->isFuture()) ? $currentExpiry : now();
                
                $user->update([
                    'plan' => 'premium',
                    'premium_expires_at' => $baseDate->addDays($lockedPayment->plan_duration_days),
                ]);

                // Send receipt email
                try {
                    Mail::to($user->email)->send(new ReceiptMail($lockedPayment));
                } catch (\Exception $e) {
                    Log::error('Failed to send Receipt Mail in PaymentService: ' . $e->getMessage());
                }

                // 3. Process Affiliate Conversion with duplicate check
                $metadata = $paystackData['metadata'] ?? [];
                $cookieToken = $metadata['affiliate_token'] ?? null;

                if ($cookieToken) {
                    try {
                        $alreadyConverted = AffiliateConversion::where('payment_id', $lockedPayment->id)->exists();

                        if (!$alreadyConverted) {
                            $click = AffiliateClick::where('cookie_token', $cookieToken)->latest()->first();
                            
                            if ($click) {
                                $affiliate = Affiliate::find($click->affiliate_id);
                                
                                if ($affiliate && $affiliate->isActive() && $affiliate->user_id !== $user->id) {
                                    $rate = (int) config('cbtwise_phase5.affiliate_commission_rate', 20);
                                    $commission = ($lockedPayment->amountNaira()) * ($rate / 100);

                                    AffiliateConversion::create([
                                        'affiliate_id'     => $affiliate->id,
                                        'referred_user_id' => $user->id,
                                        'payment_id'       => $lockedPayment->id,
                                        'commission_ngn'   => $commission,
                                        'commission_rate'  => $rate,
                                        'status'           => 'pending',
                                        'cookie_token'     => $cookieToken,
                                        'converted_at'     => now(),
                                    ]);

                                    Log::info("Affiliate conversion recorded for affiliate #{$affiliate->id}, referred user #{$user->id}");
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to record affiliate conversion: ' . $e->getMessage());
                    }
                }
            }
        });
    }
}
