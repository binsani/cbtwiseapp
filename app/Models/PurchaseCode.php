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
        'duration_days',
        'status',
        'expires_at',
        'disabled_at',
        'student_name',
        'assigned_name',
        'assigned_email',
        'assigned_password',
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
            'duration_days' => 'integer',
            'assigned_password' => 'encrypted',
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
        return $this->used_by_user_id !== null || in_array($this->status, ['redeemed', 'used'], true);
    }

    public function isAvailable(): bool
    {
        if ($this->isDisabled()) {
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

    public function isDisabled(): bool
    {
        return in_array($this->status, ['disabled', 'cancelled'], true) || $this->disabled_at !== null;
    }

    /**
     * Generate unique formatted code: CBT-XXXX-XXXX-XXXX
     */
    public static function formatCode(): string
    {
        $prefix = config('cbtwise.purchase_code_prefix', 'CBT');
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $segment = static function () use ($alphabet): string {
            return implode('', array_map(fn () => $alphabet[random_int(0, strlen($alphabet) - 1)], range(1, 4)));
        };
        $part1 = $segment();
        $part2 = $segment();
        $part3 = $segment();

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
        ?\DateTimeInterface $expiresAt = null,
        ?string $assignedName = null
    ): self {
        do {
            $code = self::formatCode();
        } while (self::where('code', $code)->exists());

        $name = $assignedName ?: $studentName;

        return self::create([
            'code'                => $code,
            'code_hash'           => hash('sha256', $code),
            'plan_duration_days'  => $durationDays,
            'duration_days'       => $durationDays,
            'status'              => 'active',
            'expires_at'          => $expiresAt,
            'student_name'        => $studentName,
            'assigned_name'       => $name,
            'assigned_email'      => self::emailForName($name, $code),
            'assigned_password'   => Str::password(12),
            'notes'               => $notes,
            'created_by_admin_id' => $adminId,
        ]);
    }

    /** Create a memorable CBTWise address and add a suffix only when required. */
    protected static function emailForName(?string $name, string $code): string
    {
        $local = Str::of($name ?: 'student-' . str_replace('-', '', $code))
            ->ascii()->lower()->replaceMatches('/[^a-z0-9]+/', '.')->trim('.')->value();
        $local = $local ?: 'student';
        $email = "{$local}@cbtwise.com.ng";
        $suffix = 2;
        while (self::where('assigned_email', $email)->exists() || User::where('email', $email)->exists()) {
            $email = "{$local}.{$suffix}@cbtwise.com.ng";
            $suffix++;
        }
        return $email;
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
