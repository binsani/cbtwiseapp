<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    /**
     * Plan prices are stored in kobo because Paystack expects the smallest
     * currency unit.
     */
    public function plans(): array
    {
        return [
            'monthly' => ['price' => 150000, 'days' => 30, 'label' => 'Monthly Plan'],
            'quarterly' => ['price' => 400000, 'days' => 90, 'label' => 'Quarterly Plan'],
            'yearly' => ['price' => 1200000, 'days' => 365, 'label' => 'Yearly Plan'],
        ];
    }

    public function plan(string $planType): ?array
    {
        return $this->plans()[$planType] ?? null;
    }

    public function publicKey(): ?string
    {
        return $this->setting('paystack_public_key', config('cbtwise.paystack.public_key'));
    }

    public function secretKey(): ?string
    {
        return $this->setting('paystack_secret_key', config('cbtwise.paystack.secret_key'));
    }

    public function baseUrl(): string
    {
        return rtrim((string) config('cbtwise.paystack.payment_url', 'https://api.paystack.co'), '/');
    }

    public function isConfigured(): bool
    {
        return filled($this->secretKey());
    }

    public function initializeTransaction(array $payload): Response
    {
        return Http::withToken((string) $this->secretKey())
            ->acceptJson()
            ->post($this->baseUrl() . '/transaction/initialize', $payload);
    }

    public function verifyTransaction(string $reference): Response
    {
        return Http::withToken((string) $this->secretKey())
            ->acceptJson()
            ->get($this->baseUrl() . '/transaction/verify/' . urlencode($reference));
    }

    public function signatureIsValid(string $payload, ?string $signature): bool
    {
        $secret = $this->secretKey();

        if (! $secret || ! $signature) {
            return false;
        }

        return hash_equals(hash_hmac('sha512', $payload, $secret), $signature);
    }

    private function setting(string $key, ?string $fallback = null): ?string
    {
        $value = SystemSetting::get($key, $fallback);

        return filled($value) ? (string) $value : null;
    }
}
