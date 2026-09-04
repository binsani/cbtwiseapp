<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    protected $table = 'admin_activity_log';

    protected $fillable = [
        'admin_id', 'action', 'subject_type', 'subject_id', 'meta', 'ip_address',
    ];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function record(int $adminId, string $action, ?string $subjectType = null, ?int $subjectId = null, array $meta = []): self
    {
        return self::create([
            'admin_id'     => $adminId,
            'action'       => $action,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'meta'         => $meta,
            'ip_address'   => request()->ip(),
        ]);
    }
}
