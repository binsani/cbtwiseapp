<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = ['type', 'title', 'message', 'is_read', 'payload'];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'payload' => 'array',
        ];
    }
}
