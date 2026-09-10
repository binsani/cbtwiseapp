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

    public function __construct()
    {
        $this->baseUri = config('cbtwise.aloc.base', 'https://dev.aloc.com.ng/api/v1');
        $this->token = config('cbtwise.aloc.token');
        $this->timeout = (float) config('cbtwise.aloc.timeout', 10.0);
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
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'x-api-key' => $this->token,
                    'X-ALOC-KEY' => $this->token,
                ])
                ->timeout($this->timeout)
                ->retry($this->retry, 200)
                ->get($endpoint, [
                    'subject' => $slug,
                    'limit' => min($limit, 40),
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $items = $json['data'] ?? [];
                    return $this->formatStationQuestions($items);
                }

                $this->lastError = "ALOC Error [{$response->status()}]: " . substr($response->body(), 0, 150);
                Log::error('ALOC Station API Error: ' . $response->status() . ' - ' . $response->body());
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
            'further mathematics' => 'further-mathematics',
            'furthermaths' => 'further-mathematics',
            'physics' => 'physics',
            'chemistry' => 'chemistry',
            'biology' => 'biology',
            'economics' => 'economics',
            'government' => 'government',
            'literature in english' => 'literature-in-english',
            'literature' => 'literature-in-english',
            'geography' => 'geography',
            'agricultural science' => 'agricultural-science',
            'agriculture' => 'agricultural-science',
            'commerce' => 'commerce',
            'financial accounting' => 'financial-accounting',
            'accounting' => 'financial-accounting',
            'civic education' => 'civic-education',
            'civic' => 'civic-education',
            'christian religious studies' => 'christian-religious-studies',
            'crk' => 'christian-religious-studies',
            'crs' => 'christian-religious-studies',
            'islamic religious studies' => 'islamic-religious-studies',
            'irk' => 'islamic-religious-studies',
            'irs' => 'islamic-religious-studies',
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
