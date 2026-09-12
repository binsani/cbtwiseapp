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
    public ?string $lastNextCursor = null;

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
    public function fetchQuestions(string $subject, int $limit = 20, ?string $examType = null, ?string $cursor = null, ?int $year = null): array
    {
        $this->lastError = null;
        $this->lastNextCursor = null;

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
                $query = array_filter([
                    'subject' => $slug,
                    // ALOC's public API reference documents `examType`, while
                    // its migration examples use `exam`. Send both during the
                    // transition so catalog-confirmed records are not filtered
                    // out by either gateway version.
                    'examType' => $examType,
                    'exam' => $examType,
                    'year' => $year,
                    'limit' => $clampedLimit,
                    'cursor' => $cursor,
                ], fn ($value) => $value !== null && $value !== '');
                $this->lastEndpoint = $endpoint . '?' . http_build_query($query);
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    // Header names are case-insensitive. Sending X-API-Key
                    // twice with different casing can be merged into an
                    // invalid value by an upstream proxy.
                    'X-API-Key' => $this->token,
                    'X-ALOC-KEY' => $this->token,
                ])
                ->timeout($this->timeout)
                ->retry($this->retry, 200)
                ->get($endpoint, $query);

                if ($response->successful()) {
                    $json = $response->json();
                    $items = $json['data'] ?? [];
                    $this->lastNextCursor = data_get($json, 'pagination.nextCursor');
                    if (empty($items)) {
                        $this->lastError = "ALOC returned no questions for {$slug}" . ($examType ? " ({$examType})" : '');
                    }
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
            $this->lastError = 'ALOC request failed: ' . $e->getMessage();
            Log::error($this->lastError);
        }

        return [];
    }

    /**
     * Return the years with questions available for a subject/exam combination.
     * The modern ALOC endpoint exposes this catalog at no credit cost.
     */
    public function fetchAvailableYears(string $subject, ?string $examType = null): array
    {
        $this->lastError = null;

        if (empty($this->token)) {
            $this->lastError = 'ALOC_API_TOKEN is empty in environment.';
            return [];
        }

        try {
            $slug = $this->normalizeSubjectSlug($subject);
            $endpoint = rtrim($this->baseUri, '/') . '/subjects/' . rawurlencode($slug) . '/years';
            $this->lastEndpoint = $endpoint;
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $this->token,
                'X-ALOC-KEY' => $this->token,
            ])
                ->timeout($this->timeout)
                ->retry($this->retry, 200)
                ->get($endpoint);

            if (!$response->successful()) {
                $this->lastError = "ALOC [{$response->status()}]: " . substr($response->body(), 0, 160);
                return [];
            }

            return collect($response->json('data', []))
                ->filter(function (array $item) use ($examType) {
                    if (!$examType) {
                        return true;
                    }

                    return in_array($examType, $item['examTypes'] ?? [], true)
                        || (int) data_get($item, "breakdown.{$examType}", 0) > 0;
                })
                ->pluck('year')
                ->filter(fn ($year) => is_numeric($year))
                ->map(fn ($year) => (int) $year)
                ->values()
                ->all();
        } catch (\Exception $e) {
            $this->lastError = 'ALOC year catalog request failed: ' . $e->getMessage();
            Log::error($this->lastError);
            return [];
        }
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
                'question' => $item['text'] ?? $item['questionHtml'] ?? $item['question'] ?? '',
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
