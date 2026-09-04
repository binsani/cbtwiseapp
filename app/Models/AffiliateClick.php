<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateClick extends Model
{
    protected $fillable = [
        'affiliate_id', 'ip', 'cookie_token', 'referral_code',
        'landing_url', 'user_agent', 'clicked_at',
    ];

    protected function casts(): array
    {
        return ['clicked_at' => 'datetime'];
    }

    public function affiliate(): BelongsTo { return $this->belongsTo(Affiliate::class); }
}
