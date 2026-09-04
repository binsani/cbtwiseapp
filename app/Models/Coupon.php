<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'usage_limit', 'used_count', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active'  => 'boolean',
            'value'      => 'decimal:2',
        ];
    }

    public function isValid(): bool
    {
        if (! $this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function discountAmount(float $originalPrice): float
    {
        if ($this->type === 'percent') {
            return round($originalPrice * ($this->value / 100), 2);
        }
        return min((float) $this->value, $originalPrice);
    }

    public function increment_used(): void
    {
        $this->increment('used_count');
    }
}
