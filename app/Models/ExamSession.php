<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSession extends Model
{
    protected $fillable = [
        'user_id', 'exam_id', 'mode', 'subjects', 'year',
        'total_questions', 'duration_seconds', 'started_at', 'submitted_at',
        'score', 'correct_count', 'status', 'score_breakdown',
    ];

    protected function casts(): array
    {
        return [
            'subjects'        => 'array',
            'score_breakdown' => 'array',
            'started_at'      => 'datetime',
            'submitted_at'    => 'datetime',
            'score'           => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isExpired(): bool
    {
        if (! $this->started_at) {
            return false;
        }
        return now()->diffInSeconds($this->started_at) >= $this->duration_seconds;
    }

    public function remainingSeconds(): int
    {
        if (! $this->started_at) {
            return $this->duration_seconds;
        }
        $elapsed = now()->diffInSeconds($this->started_at);
        return max(0, $this->duration_seconds - $elapsed);
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }
}
