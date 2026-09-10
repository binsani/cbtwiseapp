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

    public ?string $lastError = null;
    public ?string $lastEndpoint = null;

    public function __construct()
    {
        $this->token = config('cbtwise.aloc.token');
        $base = config('cbtwise.aloc.base', 'https://dev.aloc.com.ng/api/v1');

        // Any token starting with aloc_ is strictly an ALOC Station API token
        // Legacy questions.aloc.com.ng/api/v2 rejects these keys with 406
        if (!empty($this->token) && str_starts_with($this->token, 'aloc_')) {
            $base = 'https://dev.aloc.com.ng/api/v1';
        }

        $this->baseUri = $base;
        $this->timeout = (float) config('cbtwise.aloc.timeout', 15.0);
        $this->retry = (int) config('cbtwise.aloc.retry', 2);
    }

    /**
     * Fetch questions for a subject from ALOC API.
     * Supports both modern ALOC Station (dev.aloc.com.ng) and legacy (questions.aloc.com.ng).
     */
    public function fetchQuestions(string $subject, int $limit = 20): array
    {
        $this->lastError = null;

        if (empty($this->token)) {
            $this->lastError = 'ALOC_API_TOKEN is empty in environment.';
            Log::warning($this->lastError);
            return [];
        }

        try {
            $isModern = str_contains($this->baseUri, 'dev.aloc.com.ng') || str_contains($this->baseUri, '/api/v1');

            if ($isModern) {
                // Modern ALOC Station: headers x-api-key, X-ALOC-KEY
                $endpoint = rtrim($this->baseUri, '/') . '/questions';
                $slug = $this->normalizeSubjectSlug($subject);
                // ALOC Station API allows a maximum limit of 15 questions per request
                $clampedLimit = min($limit, 15);
                $this->lastEndpoint = "{$endpoint}?subject={$slug}&limit={$clampedLimit}";
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'X-API-Key' => $this->token,
                    'x-api-key' => $this->token,
                    'X-ALOC-KEY' => $this->token,
                ])
                ->timeout($this->timeout)
                ->retry($this->retry, 200)
                ->get($endpoint, [
                    'subject' => $slug,
                    'limit' => $clampedLimit,
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $items = $json['data'] ?? [];
                    return $this->formatStationQuestions($items);
                }

                $errJson = $response->json();
                $msg = $errJson['message'] ?? $errJson['error'] ?? substr($response->body(), 0, 120);
                if (isset($errJson['details'])) {
                    $msg .= ': ' . json_encode($errJson['details']);
                }
                $this->lastError = "ALOC [{$response->status()}]: {$msg}";
                Log::error("ALOC Station Error [{$response->status()} on {$this->lastEndpoint}]: " . $response->body());
            } else {
                // Legacy ALOC API fallback
                $endpoint = rtrim($this->baseUri, '/') . '/q';
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'AccessToken' => $this->token,
                ])
                ->timeout($this->timeout)
                ->retry($this->retry, 200)
                ->get($endpoint, [
                    'subject' => $subject,
                    'limit' => $limit,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? $data['questions'] ?? [];
                }

                Log::error('ALOC Legacy API Error: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('ALOC API Exception: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Map common subject names to ALOC Station URL slugs.
     */
    protected function normalizeSubjectSlug(string $subject): string
    {
        $subject = strtolower(trim($subject));
        $mapping = [
            'english' => 'english-language',
            'english language' => 'english-language',
            'mathematics' => 'mathematics',
            'maths' => 'mathematics',
            'further mathematics' => 'mathematics',
            'furthermaths' => 'mathematics',
            'physics' => 'physics',
            'chemistry' => 'chemistry',
            'biology' => 'biology',
            'economics' => 'economics',
            'government' => 'government',
            'history' => 'history',
            'insurance' => 'insurance',
            'literature in english' => 'literature-in-english',
            'literature' => 'literature-in-english',
            'geography' => 'geography',
            'commerce' => 'commerce',
            'financial accounting' => 'accounting',
            'accounting' => 'accounting',
            'civic education' => 'civic-education',
            'civic' => 'civic-education',
            'christian religious studies' => 'christian-religious-studies',
            'crk' => 'christian-religious-studies',
            'crs' => 'christian-religious-studies',
        ];

        return $mapping[$subject] ?? str_replace(' ', '-', $subject);
    }

    /**
     * Convert ALOC Station JSON format to CBTwise question structure.
     */
    protected function formatStationQuestions(array $items): array
    {
        $formatted = [];
        foreach ($items as $item) {
            $options = $item['options'] ?? [];
            $formatted[] = [
                'question' => $item['text'] ?? $item['question'] ?? '',
                'option' => [
                    'a' => $options['A'] ?? $options['a'] ?? '',
                    'b' => $options['B'] ?? $options['b'] ?? '',
                    'c' => $options['C'] ?? $options['c'] ?? '',
                    'd' => $options['D'] ?? $options['d'] ?? '',
                    'e' => $options['E'] ?? $options['e'] ?? null,
                ],
                'answer' => strtolower($item['correctAnswer'] ?? $item['answer'] ?? 'a'),
                'year' => $item['year'] ?? now()->year,
                'image' => $item['imageUrl'] ?? $item['image'] ?? null,
                'solution' => $item['explanation'] ?? $item['solution'] ?? null,
                'section' => $item['section'] ?? null,
                'topic' => $item['metadata']['topic'] ?? null,
            ];
        }
        return $formatted;
    }
}
