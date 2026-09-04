<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SeoPage extends Model
{
    protected $fillable = [
        'slug', 'exam_id', 'subject_id', 'year',
        'title', 'meta_description', 'h1', 'body_md',
        'schema_json', 'view_count', 'indexed_at', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'schema_json'  => 'array',
            'published_at' => 'datetime',
            'indexed_at'   => 'datetime',
            'year'         => 'integer',
            'view_count'   => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function incrementView(): void
    {
        $this->increment('view_count');
    }

    public static function buildSlug(string $examSlug, string $subjectSlug, int $year): string
    {
        return "{$examSlug}-{$subjectSlug}-{$year}";
    }
}
