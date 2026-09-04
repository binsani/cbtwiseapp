<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id', 'status', 'balance_ngn', 'total_earned_ngn',
        'bank_code', 'account_number', 'account_name', 'payout_method', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'balance_ngn'      => 'decimal:2',
            'total_earned_ngn' => 'decimal:2',
            'approved_at'      => 'datetime',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function clicks(): HasMany { return $this->hasMany(AffiliateClick::class); }
    public function conversions(): HasMany { return $this->hasMany(AffiliateConversion::class); }
    public function payouts(): HasMany { return $this->hasMany(AffiliatePayout::class); }

    public function isActive(): bool { return $this->status === 'active'; }

    public function canRequestPayout(): bool
    {
        return $this->isActive() && $this->balance_ngn >= config('cbtwise_phase5.affiliate_min_payout', 5000);
    }

    public function pendingEarnings(): float
    {
        return (float) $this->conversions()->where('status', 'pending')->sum('commission_ngn');
    }
}
