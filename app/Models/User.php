<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'state', 'school',
        'exam_year', 'plan', 'premium_expires_at', 'daily_question_count',
        'daily_count_reset_at', 'study_streak_days', 'last_active_date',
        'google_id', 'avatar', 'email_verified_at',
        'referral_code', 'referred_by', 'streak_freeze_tokens',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'premium_expires_at'   => 'datetime',
            'daily_count_reset_at' => 'datetime',
            'last_active_date'     => 'date',
            'exam_year'            => 'integer',
            'daily_question_count' => 'integer',
            'study_streak_days'    => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function examSessions(): HasMany
    {
        return $this->hasMany(ExamSession::class);
    }

    public function affiliate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Affiliate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function questionReports(): HasMany
    {
        return $this->hasMany(QuestionReport::class);
    }

    public function purchaseCodesUsed(): HasMany
    {
        return $this->hasMany(PurchaseCode::class, 'used_by_user_id');
    }

    public function purchaseCodesCreated(): HasMany
    {
        return $this->hasMany(PurchaseCode::class, 'created_by_admin_id');
    }

    public function aiLogs(): HasMany
    {
        return $this->hasMany(AiLog::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referralReceived(): BelongsTo
    {
        return $this->belongsTo(Referral::class, 'id', 'referred_user_id');
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->userNotifications()->whereNull('read_at')->count();
    }

    public function isBookmarked(int $questionId): bool
    {
        return $this->bookmarks()->where('question_id', $questionId)->exists();
    }

    public function toggleBookmark(int $questionId): bool
    {
        $bookmark = $this->bookmarks()->where('question_id', $questionId)->first();
        if ($bookmark) {
            $bookmark->delete();
            return false;
        }
        $this->bookmarks()->create(['question_id' => $questionId]);
        return true;
    }

    // ── Plan Helpers ──────────────────────────────────────────────────────────

    public function isPremium(): bool
    {
        return $this->plan === 'premium'
            && ($this->premium_expires_at === null || $this->premium_expires_at->isFuture());
    }

    public function isFree(): bool
    {
        return ! $this->isPremium();
    }

    public function hasReachedDailyLimit(): bool
    {
        if ($this->isPremium()) {
            return false;
        }

        $limit = config('cbtwise.free_daily_limit', 20);
        $tz    = config('cbtwise.daily_reset_timezone', 'Africa/Lagos');
        $today = now($tz)->startOfDay();

        // Reset counter if it's a new WAT day
        if ($this->daily_count_reset_at === null || $this->daily_count_reset_at->lt($today)) {
            $this->update(['daily_question_count' => 0, 'daily_count_reset_at' => now()]);
            return false;
        }

        return $this->daily_question_count >= $limit;
    }

    public function incrementDailyCount(int $by = 1): void
    {
        $this->increment('daily_question_count', $by);
    }

    // ── Streak Helpers ────────────────────────────────────────────────────────

    public function updateStreak(): void
    {
        $tz       = config('cbtwise.daily_reset_timezone', 'Africa/Lagos');
        $today    = now($tz)->toDateString();
        $yesterday = now($tz)->subDay()->toDateString();

        if ($this->last_active_date?->toDateString() === $yesterday) {
            $this->increment('study_streak_days');
        } elseif ($this->last_active_date?->toDateString() !== $today) {
            $this->update(['study_streak_days' => 1]);
        }

        $this->update(['last_active_date' => $today]);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePremium($query)
    {
        return $query->where('plan', 'premium')
            ->where(function ($q) {
                $q->whereNull('premium_expires_at')
                  ->orWhere('premium_expires_at', '>', now());
            });
    }

    public function scopeFree($query)
    {
        return $query->where('plan', 'free');
    }
}
