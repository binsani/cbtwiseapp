<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'status',
        'reply',
        'replied_at',
        'internal_notes',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }

    public function markAsSpam(): void
    {
        $this->update(['status' => 'spam']);
    }

    public function recordReply(string $replyText): void
    {
        $this->update([
            'reply' => $replyText,
            'replied_at' => now(),
            'status' => 'replied',
        ]);
    }
}
