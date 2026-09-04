<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAnswer extends Model
{
    protected $fillable = [
        'exam_session_id', 'question_id', 'selected_option',
        'is_correct', 'time_spent_seconds', 'flagged_for_review',
    ];

    protected function casts(): array
    {
        return [
            'is_correct'         => 'boolean',
            'flagged_for_review' => 'boolean',
            'time_spent_seconds' => 'integer',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeFlagged($query)
    {
        return $query->where('flagged_for_review', true);
    }

    public function scopeAnswered($query)
    {
        return $query->whereNotNull('selected_option');
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('selected_option');
    }
}
