<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Mail\ReceiptMail;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Initialize a Paystack transaction and redirect the user.
     */
    public function initialize(Request $request)
    {
        $request->validate([
            'plan_type' => 'required|in:monthly,quarterly,yearly',
        ]);

        $user = Auth::user();
        $planType = $request->plan_type;

        // Plan configurations: prices in kobo (100 kobo = ₦1) & durations in days
        $plans = [
            'monthly' => ['price' => 150000, 'days' => 30],
            'quarterly' => ['price' => 400000, 'days' => 90],
            'yearly' => ['price' => 1200000, 'days' => 365],
        ];

        $plan = $plans[$planType];
        $secretKey = config('cbtwise.paystack.secret_key');

        if (!$secretKey) {
            Log::error('Paystack Secret Key is missing in configuration.');
            return redirect()->back()->with('error', 'Payment configuration error. Please contact support.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post(config('cbtwise.paystack.payment_url', 'https://api.paystack.co') . '/transaction/initialize', [
                'email' => $user->email,
                'amount' => $plan['price'],
                'callback_url' => route('payment.callback'),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_type' => $planType,
                    'duration_days' => $plan['days'],
                    'affiliate_token' => $request->cookie('cbtwise_aff_token'),
                ],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $authUrl = $result['data']['authorization_url'] ?? null;
                $reference = $result['data']['reference'] ?? null;

                if ($authUrl && $reference) {
                    // Create pending payment row
                    Payment::create([
                        'user_id' => $user->id,
                        'paystack_reference' => $reference,
                        'amount_kobo' => $plan['price'],
                        'status' => 'pending',
                        'plan_duration_days' => $plan['days'],
                        'plan_type' => $planType,
                    ]);

                    return redirect()->away($authUrl);
                }
            }

            Log::error('Paystack Initialization Failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paystack Initialization Exception: ' . $e->getMessage());
        }

        return redirect()->back()->with('error', 'Unable to initialize checkout. Please try again.');
    }

    /**
     * Handle the transaction callback from Paystack.
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('pricing')->with('error', 'Invalid payment reference.');
        }

        $secretKey = config('cbtwise.paystack.secret_key');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
            ])->get(config('cbtwise.paystack.payment_url', 'https://api.paystack.co') . '/transaction/verify/' . $reference);

            if ($response->successful()) {
                $result = $response->json();
                $data = $result['data'] ?? [];

                if (($data['status'] ?? '') === 'success') {
                    $payment = Payment::where('paystack_reference', $reference)->first();

                    if ($payment) {
                        if ($payment->status === 'pending') {
                            PaymentService::processSuccess($payment, $data);
                        }

                        return redirect()->route('dashboard')->with('success', 'Your account has been upgraded to Premium successfully!');
                    }
                }
            }

            Log::error('Paystack Verification Failed: ' . $response->body());
            
            // Mark payment as failed if it was pending
            Payment::where('paystack_reference', $reference)
                ->where('status', 'pending')
                ->update(['status' => 'failed']);

        } catch (\Exception $e) {
            Log::error('Paystack Verification Exception: ' . $e->getMessage());
        }

        return redirect()->route('pricing')->with('error', 'Payment verification failed. If you were debited, please contact support.');
    }
}
