<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PurchaseCode extends Model
{
    protected $fillable = [
        'code',
        'code_hash',
        'plan_duration_days',
        'status',
        'expires_at',
        'disabled_at',
        'student_name',
        'used_by_user_id',
        'used_at',
        'notes',
        'created_by_admin_id',
    ];

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
            'disabled_at' => 'datetime',
            'plan_duration_days' => 'integer',
        ];
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by_user_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function isUsed(): bool
    {
        return $this->used_by_user_id !== null || $this->status === 'redeemed';
    }

    public function isAvailable(): bool
    {
        if ($this->status === 'disabled' || $this->disabled_at !== null) {
            return false;
        }
        if ($this->isUsed()) {
            return false;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Generate unique formatted code: CBT-XXXX-XXXX-XXXX
     */
    public static function formatCode(): string
    {
        $prefix = config('cbtwise.purchase_code_prefix', 'CBT');
        $part1 = strtoupper(Str::random(4));
        $part2 = strtoupper(Str::random(4));
        $part3 = strtoupper(Str::random(4));

        return "{$prefix}-{$part1}-{$part2}-{$part3}";
    }

    /**
     * Generate a single purchase code.
     */
    public static function generate(
        int $adminId,
        int $durationDays = 30,
        ?string $studentName = null,
        ?string $notes = null,
        ?\DateTimeInterface $expiresAt = null
    ): self {
        do {
            $code = self::formatCode();
        } while (self::where('code', $code)->exists());

        return self::create([
            'code'                => $code,
            'code_hash'           => hash('sha256', $code),
            'plan_duration_days'  => $durationDays,
            'status'              => 'available',
            'expires_at'          => $expiresAt,
            'student_name'        => $studentName,
            'notes'               => $notes,
            'created_by_admin_id' => $adminId,
        ]);
    }

    /**
     * Generate bulk purchase codes.
     */
    public static function generateBulk(
        int $adminId,
        int $count = 10,
        int $durationDays = 30,
        ?string $notes = null,
        ?\DateTimeInterface $expiresAt = null
    ): array {
        $created = [];
        for ($i = 0; $i < $count; $i++) {
            $created[] = self::generate($adminId, $durationDays, null, $notes, $expiresAt);
        }
        return $created;
    }

    public function disable(): void
    {
        $this->update([
            'status' => 'disabled',
            'disabled_at' => now(),
        ]);
    }

    public function restore(): void
    {
        $newStatus = $this->used_by_user_id ? 'redeemed' : 'available';
        $this->update([
            'status' => $newStatus,
            'disabled_at' => null,
        ]);
    }

    public function scopeUnused($query)
    {
        return $query->whereNull('used_by_user_id')->where('status', '!=', 'disabled');
    }
}
