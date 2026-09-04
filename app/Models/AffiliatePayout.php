<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliatePayout extends Model
{
    protected $fillable = [
        'affiliate_id', 'amount_ngn', 'paystack_reference',
        'paystack_transfer_code', 'status', 'batch_date', 'failure_reason', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_ngn' => 'decimal:2',
            'batch_date' => 'date',
            'paid_at'    => 'datetime',
        ];
    }

    public function affiliate(): BelongsTo { return $this->belongsTo(Affiliate::class); }
}
