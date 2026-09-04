<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Question extends Model
{
    protected $fillable = [
        'exam_id', 'subject_id', 'topic_id', 'created_by', 'year',
        'question_text', 'question_image',
        'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
        'correct_option', 'explanation', 'difficulty', 'source', 'dedupe_hash',
        'times_served', 'times_correct', 'reports_count', 'is_flagged',
    ];

    protected function casts(): array
    {
        return [
            'is_flagged'    => 'boolean',
            'year'          => 'integer',
            'times_served'  => 'integer',
            'times_correct' => 'integer',
            'reports_count' => 'integer',
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

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function examAnswers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(QuestionReport::class);
    }

    // ── Dedupe Hash ───────────────────────────────────────────────────────────

    /**
     * Generate a SHA-256 hash of the first 60 normalised characters of the question text.
     * Used to detect duplicate questions from different sources.
     */
    public static function dedupeHash(string $text): string
    {
        $normalised = Str::lower(
            preg_replace('/\s+/', ' ', trim(strip_tags($text)))
        );
        $slice = mb_substr($normalised, 0, config('cbtwise.dedupe_char_length', 60));

        return hash('sha256', $slice);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeForExam($query, int $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeForSubject($query, int $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeForYear($query, ?int $year)
    {
        return $year ? $query->where('year', $year) : $query;
    }

    public function scopeNotFlagged($query)
    {
        return $query->where('is_flagged', false);
    }

    /**
     * Weighted random — questions served less get higher priority.
     * Uses MySQL RAND() trick weighted by inverse of times_served.
     */
    public function scopeWeightedRandom($query)
    {
        $driver = $query->getConnection()->getDriverName();
        $randomFunc = $driver === 'sqlite' ? 'abs(random()) / 9223372036854775807' : 'rand()';
        return $query->orderByRaw("$randomFunc * (1 / (times_served + 1)) DESC");
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function incrementServed(): void
    {
        $this->increment('times_served');
    }

    public function incrementCorrect(): void
    {
        $this->increment('times_correct');
    }

    public function autoFlagIfNeeded(): void
    {
        if ($this->reports_count >= 3 && ! $this->is_flagged) {
            $this->update(['is_flagged' => true]);
        }
    }

    public function getOptions(): array
    {
        return array_filter([
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
            'e' => $this->option_e,
        ]);
    }
}
