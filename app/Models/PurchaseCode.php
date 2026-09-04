<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PurchaseCode extends Model
{
    protected $fillable = [
        'code', 'plan_duration_days', 'student_name', 'used_by_user_id', 'used_at', 'notes', 'created_by_admin_id',
    ];

    protected function casts(): array
    {
        return ['used_at' => 'datetime', 'plan_duration_days' => 'integer'];
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
        return $this->used_by_user_id !== null;
    }

    public static function generate(int $adminId, int $durationDays = 30, ?string $studentName = null, ?string $notes = null): self
    {
        $prefix = config('cbtwise.purchase_code_prefix', 'CBT');
        do {
            $code = $prefix . '-' . strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return self::create([
            'code'               => $code,
            'plan_duration_days' => $durationDays,
            'student_name'       => $studentName,
            'notes'              => $notes,
            'created_by_admin_id' => $adminId,
        ]);
    }

    public function scopeUnused($query)
    {
        return $query->whereNull('used_by_user_id');
    }
}
