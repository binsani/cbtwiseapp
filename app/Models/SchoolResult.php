<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolResult extends Model
{
    protected $fillable = [
        'assignment_id', 'user_id', 'session_id', 'score',
        'correct_count', 'total_questions', 'rank', 'time_taken_secs', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'correct_count' => 'integer',
            'total_questions' => 'integer',
            'rank' => 'integer',
            'time_taken_secs' => 'integer',
            'submitted_at' => 'datetime',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(SchoolAssignment::class, 'assignment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'session_id');
    }
}
