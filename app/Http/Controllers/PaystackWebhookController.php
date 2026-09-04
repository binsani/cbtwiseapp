<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Mail\ReceiptMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaystackWebhookController extends Controller
{
    /**
     * Handle incoming webhooks from Paystack.
     */
    public function handle(Request $request)
    {
        $signature = $request->header('x-paystack-signature');
        $secretKey = config('cbtwise.paystack.secret_key');

        if (!$signature || !$secretKey) {
            Log::warning('Paystack Webhook received without signature or secret key config.');
            return response()->json(['message' => 'Signature or Secret missing'], 400);
        }

        // Verify signature
        $payload = $request->getContent();
        $computedSignature = hash_hmac('sha512', $payload, $secretKey);

        if ($computedSignature !== $signature) {
            Log::warning('Paystack Webhook signature mismatch.');
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $event = $request->input('event');
        $data = $request->input('data', []);

        Log::info('Paystack Webhook Event: ' . $event);

        if ($event === 'charge.success') {
            $reference = $data['reference'] ?? null;
            $email = $data['customer']['email'] ?? null;
            $amountKobo = $data['amount'] ?? 0;
            $metadata = $data['metadata'] ?? [];

            if ($reference) {
                $payment = Payment::where('paystack_reference', $reference)->first();
                $user = null;

                if ($payment) {
                    $user = User::find($payment->user_id);
                } elseif ($email) {
                    $user = User::where('email', $email)->first();
                }

                if ($user) {
                    $durationDays = $metadata['duration_days'] ?? 30; // fallback monthly
                    $planType = $metadata['plan_type'] ?? 'monthly';

                    if (!$payment) {
                        // Create payment if not exists
                        $payment = Payment::create([
                            'user_id' => $user->id,
                            'paystack_reference' => $reference,
                            'amount_kobo' => $amountKobo,
                            'status' => 'pending',
                            'plan_duration_days' => $durationDays,
                            'plan_type' => $planType,
                        ]);
                    }

                    if ($payment->status === 'pending') {
                        \App\Services\PaymentService::processSuccess($payment, $data);
                    }
                }
            }
        }

        return response()->json(['message' => 'Webhook Handled Successfully'], 200);
    }
}
