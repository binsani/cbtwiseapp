<?php

namespace App\Http\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlocApiClient
{
    protected string $baseUri;
    protected ?string $token;
    protected float $timeout;
    protected int $retry;

    public function __construct()
    {
        $this->baseUri = config('cbtwise.aloc.base', 'https://questions.aloc.com.ng/api/v2');
        $this->token = config('cbtwise.aloc.token');
        $this->timeout = (float) config('cbtwise.aloc.timeout', 3.0);
        $this->retry = (int) config('cbtwise.aloc.retry', 1);
    }

    /**
     * Fetch questions for a subject from the ALOC API.
     */
    public function fetchQuestions(string $subject, int $limit = 20): array
    {
        try {
            $headers = [
                'Accept' => 'application/json',
            ];

            if ($this->token) {
                $headers['AccessToken'] = $this->token;
            }

            // Standard ALOC Endpoint: /q?subject={subject}&limit={limit}
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->retry($this->retry, 100)
                ->get($this->baseUri . '/q', [
                    'subject' => $subject,
                    'limit' => $limit,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Standard ALOC response has data in "data" or "questions" key
                // Usually ALOC returns: { "status": 200, "data": [ ... ] }
                return $data['data'] ?? $data['questions'] ?? [];
            }

            Log::error('ALOC API Error: ' . $response->status() . ' - ' . $response->body());
        } catch (\Exception $e) {
            Log::error('ALOC API Exception: ' . $e->getMessage());
        }

        return [];
    }
}
