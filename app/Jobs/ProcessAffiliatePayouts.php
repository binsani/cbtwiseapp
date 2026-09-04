<?php

namespace App\Jobs;

use App\Models\AffiliatePayout;
use App\Models\AffiliateConversion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessAffiliatePayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $secretKey = config('cbtwise.paystack.secret_key');
        if (!$secretKey) {
            Log::error('Paystack Secret Key is missing. Payouts cannot be processed.');
            return;
        }

        $pendingPayouts = AffiliatePayout::where('status', 'pending')
            ->with('affiliate')
            ->get();

        foreach ($pendingPayouts as $payout) {
            $affiliate = $payout->affiliate;

            if (!$affiliate || !$affiliate->bank_code || !$affiliate->account_number) {
                $payout->update([
                    'status' => 'failed',
                    'failure_reason' => 'Missing bank account details on affiliate profile.',
                ]);
                continue;
            }

            try {
                // Step 1: Create Transfer Recipient
                $recipientResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $secretKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.paystack.co/transferrecipient', [
                    'type' => 'nuban',
                    'name' => $affiliate->account_name ?: $affiliate->user->name,
                    'account_number' => $affiliate->account_number,
                    'bank_code' => $affiliate->bank_code,
                    'currency' => 'NGN',
                ]);

                if (!$recipientResponse->successful()) {
                    $payout->update([
                        'status' => 'failed',
                        'failure_reason' => 'Paystack recipient creation failed: ' . $recipientResponse->json('message', 'Unknown error'),
                    ]);
                    continue;
                }

                $recipientCode = $recipientResponse->json('data.recipient_code');
                if (!$recipientCode) {
                    $payout->update([
                        'status' => 'failed',
                        'failure_reason' => 'Recipient code not returned by Paystack.',
                    ]);
                    continue;
                }

                // Step 2: Initiate Transfer
                // Paystack transfers require amount in kobo
                $amountKobo = (int) ($payout->amount_ngn * 100);
                $reference = 'PAY-' . $payout->id . '-' . time();

                $transferResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $secretKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.paystack.co/transfer', [
                    'source' => 'balance',
                    'amount' => $amountKobo,
                    'recipient' => $recipientCode,
                    'reference' => $reference,
                    'reason' => 'CBTWise Affiliate Payout',
                ]);

                if ($transferResponse->successful()) {
                    $transferData = $transferResponse->json('data');
                    $paystackStatus = $transferData['status'] ?? 'processing';
                    
                    // Map Paystack status to database status
                    $dbStatus = 'processing';
                    if ($paystackStatus === 'success') {
                        $dbStatus = 'success';
                    } elseif ($paystackStatus === 'failed') {
                        $dbStatus = 'failed';
                    }

                    $payout->update([
                        'status' => $dbStatus,
                        'paystack_reference' => $reference,
                        'paystack_transfer_code' => $transferData['transfer_code'] ?? null,
                        'paid_at' => $dbStatus === 'success' ? now() : null,
                        'failure_reason' => $dbStatus === 'failed' ? ($transferData['gateway_response'] ?? 'Transaction failed') : null,
                    ]);

                    if ($dbStatus === 'success') {
                        // Mark conversions as paid
                        AffiliateConversion::where('affiliate_id', $affiliate->id)
                            ->where('status', 'pending')
                            ->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                            ]);

                        // Add to total earned
                        $affiliate->increment('total_earned_ngn', $payout->amount_ngn);
                    }
                } else {
                    $payout->update([
                        'status' => 'failed',
                        'failure_reason' => 'Paystack transfer request failed: ' . $transferResponse->json('message', 'Unknown error'),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Affiliate payout error on ID {$payout->id}: " . $e->getMessage());
                $payout->update([
                    'status' => 'failed',
                    'failure_reason' => 'Internal exception: ' . $e->getMessage(),
                ]);
            }
        }
    }
}
