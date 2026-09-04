<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateConversion extends Model
{
    protected $fillable = [
        'affiliate_id', 'referred_user_id', 'payment_id', 'commission_ngn',
        'commission_rate', 'status', 'cookie_token', 'converted_at', 'approved_at', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'commission_ngn' => 'decimal:2',
            'converted_at'  => 'datetime',
            'approved_at'   => 'datetime',
            'paid_at'       => 'datetime',
        ];
    }

    public function affiliate(): BelongsTo { return $this->belongsTo(Affiliate::class); }
    public function referredUser(): BelongsTo { return $this->belongsTo(User::class, 'referred_user_id'); }
    public function payment(): BelongsTo { return $this->belongsTo(Payment::class); }
}
