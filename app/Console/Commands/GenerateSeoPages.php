<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\Question;
use App\Models\SeoPage;
use App\Models\Subject;
use App\Services\OpenAiClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSeoPages extends Command
{
    protected $signature   = 'cbtwise:generate-seo-pages
                                {--dry-run : Preview combos without writing to DB}
                                {--force : Overwrite existing pages}
                                {--exam= : Only generate for this exam slug}
                                {--limit=0 : Max pages to generate (0 = unlimited)}';

    protected $description = 'Generate programmatic SEO landing pages for every exam × subject × year combo.';

    public function __construct(private readonly OpenAiClient $ai)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $force  = $this->option('force');
        $limit  = (int) $this->option('limit');

        $this->info('🔍 Scanning question combinations...');

        // Fetch all distinct (exam, subject, year) combos that have at least 5 questions
        $combos = Question::query()
            ->selectRaw('exam_id, subject_id, year, COUNT(*) as q_count')
            ->where('is_flagged', false)
            ->groupBy('exam_id', 'subject_id', 'year')
            ->having('q_count', '>=', 5)
            ->when($this->option('exam'), function ($q) {
                $examSlug = $this->option('exam');
                $exam = Exam::where('slug', $examSlug)->first();
                if ($exam) {
                    $q->where('exam_id', $exam->id);
                }
            })
            ->get();

        $this->info("Found {$combos->count()} combos.");

        if ($dryRun) {
            $this->table(['Exam ID', 'Subject ID', 'Year', 'Questions'], $combos->map(fn ($c) => [
                $c->exam_id, $c->subject_id, $c->year, $c->q_count,
            ])->toArray());
            return self::SUCCESS;
        }

        $generated = 0;

        foreach ($combos as $combo) {
            if ($limit > 0 && $generated >= $limit) {
                break;
            }

            $exam    = Exam::find($combo->exam_id);
            $subject = Subject::find($combo->subject_id);

            if (! $exam || ! $subject) {
                continue;
            }

            $slug = SeoPage::buildSlug($exam->slug, $subject->slug, $combo->year);

            if (! $force && SeoPage::where('slug', $slug)->exists()) {
                $this->line("  ↷ Skip (exists): {$slug}");
                continue;
            }

            $this->line("  ✦ Generating: {$slug}");

            $title       = "{$exam->name} {$subject->name} {$combo->year} Past Questions & Answers";
            $h1          = "Practice {$exam->name} {$subject->name} {$combo->year} Questions";
            $metaDesc    = "Practise {$combo->q_count}+ {$exam->name} {$subject->name} {$combo->year} past questions with answers and explanations. Free CBT practice at CBTWise.";
            $bodyMd      = $this->generateBodyMd($exam->name, $subject->name, $combo->year, $combo->q_count, $slug);
            $schemaJson  = $this->buildSchemaJson($title, $metaDesc, $exam->name, $subject->name, $combo->year);

            SeoPage::updateOrCreate(
                ['slug' => $slug],
                [
                    'exam_id'          => $exam->id,
                    'subject_id'       => $subject->id,
                    'year'             => $combo->year,
                    'title'            => $title,
                    'meta_description' => $metaDesc,
                    'h1'               => $h1,
                    'body_md'          => $bodyMd,
                    'schema_json'      => $schemaJson,
                    'published_at'     => now(),
                ]
            );

            $this->pingGoogleIndexing(url("/{$exam->slug}/{$subject->slug}/{$combo->year}"));

            $generated++;
        }

        $this->info("✅ Generated {$generated} SEO pages.");
        $this->regenerateSitemap();

        return self::SUCCESS;
    }

    private function generateBodyMd(string $exam, string $subject, int $year, int $count, string $cacheKey): string
    {
        $key = "seo_body_md:{$cacheKey}";

        return Cache::remember($key, now()->addDays(30), function () use ($exam, $subject, $year, $count) {
            try {
                $prompt = "Write a 120-word SEO-optimised introductory paragraph for a page titled \"{$exam} {$subject} {$year} Past Questions\". "
                    . "Mention that there are {$count}+ questions with answers and explanations. "
                    . "Target Nigerian secondary school students preparing for {$exam}. "
                    . "Use a helpful, encouraging tone. Plain text only, no markdown headers.";

                return $this->ai->complete($prompt, maxTokens: 200);
            } catch (\Throwable $e) {
                Log::warning("SEO body AI generation failed for {$cacheKey}: " . $e->getMessage());

                // Fallback template
                return "Prepare for your {$exam} {$subject} examination with our comprehensive collection of {$year} past questions. "
                    . "CBTWise gives you access to {$count}+ authentic {$subject} questions from the {$year} {$exam} exam, "
                    . "complete with detailed answers and step-by-step explanations. "
                    . "Practice in timed CBT mode to simulate real exam conditions and track your performance as you improve.";
            }
        });
    }

    private function buildSchemaJson(string $title, string $metaDesc, string $exam, string $subject, int $year): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'       => 'Course',
                    'name'        => $title,
                    'description' => $metaDesc,
                    'provider'    => [
                        '@type' => 'Organization',
                        'name'  => 'CBTWise',
                        'url'   => config('app.url'),
                    ],
                ],
                [
                    '@type'       => 'Quiz',
                    'name'        => "{$exam} {$subject} {$year} Practice Quiz",
                    'educationalLevel' => 'Secondary',
                    'about'       => $subject,
                ],
                [
                    '@type'           => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => config('app.url')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $exam,     'item' => config('app.url') . "/exams/{$exam}"],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $subject,  'item' => '#'],
                    ],
                ],
            ],
        ];
    }

    private function pingGoogleIndexing(string $pageUrl): void
    {
        $apiKey = config('services.google.indexing_api_key');
        if (! $apiKey) {
            return;
        }

        try {
            Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://indexing.googleapis.com/v3/urlNotifications:publish?key={$apiKey}", [
                    'url'  => $pageUrl,
                    'type' => 'URL_UPDATED',
                ]);
        } catch (\Throwable $e) {
            Log::warning("Google Indexing ping failed for {$pageUrl}: " . $e->getMessage());
        }
    }

    private function regenerateSitemap(): void
    {
        try {
            $this->call('sitemap:generate');
            $this->info('🗺  Sitemap regenerated.');
        } catch (\Throwable $e) {
            Log::warning('Sitemap regeneration failed: ' . $e->getMessage());
        }
    }
}
