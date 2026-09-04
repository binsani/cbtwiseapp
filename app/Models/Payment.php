<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'paystack_reference', 'amount_kobo',
        'status', 'plan_duration_days', 'plan_type', 'paystack_data', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paystack_data'      => 'array',
            'paid_at'            => 'datetime',
            'amount_kobo'        => 'integer',
            'plan_duration_days' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function amountNaira(): float
    {
        return $this->amount_kobo / 100;
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
}
