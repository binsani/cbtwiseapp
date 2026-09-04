<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionReport extends Model
{
    protected $fillable = [
        'question_id', 'user_id', 'reason', 'notes',
        'status', 'reviewed_by', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function markFixed(int $adminId): void
    {
        $this->update(['status' => 'fixed', 'reviewed_by' => $adminId, 'reviewed_at' => now()]);
    }

    public function dismiss(int $adminId): void
    {
        $this->update(['status' => 'dismissed', 'reviewed_by' => $adminId, 'reviewed_at' => now()]);
    }
}
