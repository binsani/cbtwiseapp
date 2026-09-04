<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    // Disable default timestamps, we only have created_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'feature',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'total_tokens' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
