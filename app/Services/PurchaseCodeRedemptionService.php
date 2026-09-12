<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\PurchaseCode;
use App\Models\PurchaseCodeRedemptionAttempt;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PurchaseCodeRedemptionService
{
    public function redeem(string $rawCode, ?string $ip = null): array
    {
        $code = strtoupper(trim($rawCode));
        try {
            $result = DB::transaction(function () use ($code) {
                $purchaseCode = PurchaseCode::where('code', $code)->lockForUpdate()->first();
                if (!$purchaseCode || !$purchaseCode->isAvailable()) {
                    throw ValidationException::withMessages(['code' => 'This code cannot be redeemed.']);
                }
                if (!$purchaseCode->assigned_email || !$purchaseCode->assigned_password) {
                    throw ValidationException::withMessages(['code' => 'This code has not been assigned credentials.']);
                }
                if (User::where('email', $purchaseCode->assigned_email)->exists()) {
                    throw ValidationException::withMessages(['code' => 'This code cannot be redeemed.']);
                }

                $password = $purchaseCode->assigned_password;
                $user = User::create([
                    'name' => $purchaseCode->assigned_name ?: 'CBTWise Student',
                    'email' => $purchaseCode->assigned_email,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                    'plan' => 'premium',
                    'premium_expires_at' => now()->addDays($purchaseCode->duration_days ?: $purchaseCode->plan_duration_days),
                ]);
                $user->assignRole('user');
                $days = $purchaseCode->duration_days ?: $purchaseCode->plan_duration_days;
                Subscription::create(['user_id' => $user->id, 'plan' => "{$days}-day Premium", 'status' => 'active', 'starts_at' => now(), 'ends_at' => now()->addDays($days), 'payment_reference' => "CODE:{$purchaseCode->code}"]);
                $purchaseCode->update(['status' => 'used', 'used_by_user_id' => $user->id, 'used_at' => now()]);
                AdminNotification::create(['type' => 'code_redeemed', 'title' => 'Purchase Code Redeemed', 'message' => "{$purchaseCode->code} was redeemed.", 'payload' => ['purchase_code_id' => $purchaseCode->id, 'user_id' => $user->id]]);
                return compact('user', 'password');
            });
            $this->audit($code, $ip, 'success');
            return $result;
        } catch (\Throwable $exception) {
            $this->audit($code, $ip, 'failed', $exception instanceof ValidationException ? 'unavailable' : 'error');
            throw $exception;
        }
    }

    private function audit(string $code, ?string $ip, string $result, ?string $reason = null): void
    {
        PurchaseCodeRedemptionAttempt::create(['code_fingerprint' => hash('sha256', $code), 'ip_address' => $ip, 'result' => $result, 'reason' => $reason]);
    }
}
