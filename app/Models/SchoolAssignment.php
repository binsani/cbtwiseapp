<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolAssignment extends Model
{
    protected $fillable = [
        'school_id', 'creator_id', 'exam_id', 'subject_id', 'title',
        'instructions', 'question_count', 'time_limit_mins', 'start_at',
        'end_at', 'allow_retake', 'show_answers_after', 'year_filter',
    ];

    protected function casts(): array
    {
        return [
            'question_count' => 'integer',
            'time_limit_mins' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'allow_retake' => 'boolean',
            'show_answers_after' => 'boolean',
            'year_filter' => 'array',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(SchoolResult::class, 'assignment_id');
    }

    public function isAvailable(): bool
    {
        $now = now();
        return ($this->start_at === null || $this->start_at <= $now) &&
               ($this->end_at === null || $this->end_at >= $now);
    }
}
