<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    protected $table = 'admin_activity_log';

    protected $fillable = [
        'admin_id',
        'action',
        'subject_type',
        'subject_id',
        'meta',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Record an administrative activity log.
     */
    public static function record(
        int $adminId,
        string $action,
        ?string $subjectType = null,
        ?int $subjectId = null,
        array $meta = [],
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        // Sanitize sensitive fields from values
        $sensitiveKeys = ['password', 'remember_token', 'token', 'secret', 'secret_key', 'api_key'];
        
        $sanitize = function (?array $arr) use ($sensitiveKeys) {
            if (!$arr) return null;
            foreach ($sensitiveKeys as $key) {
                if (isset($arr[$key])) {
                    $arr[$key] = '********';
                }
            }
            return $arr;
        };

        return self::create([
            'admin_id'     => $adminId,
            'action'       => $action,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'meta'         => $meta,
            'old_values'   => $sanitize($oldValues),
            'new_values'   => $sanitize($newValues),
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);
    }
}
