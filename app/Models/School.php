<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'logo', 'subdomain', 'custom_email_sender',
        'tier', 'seat_limit', 'seats_used', 'owner_id', 'status',
        'contact_email', 'contact_phone', 'state', 'address', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'seat_limit' => 'integer',
            'seats_used' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(SchoolMember::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'school_members')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    public function invites(): HasMany
    {
        return $this->hasMany(SchoolInvite::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SchoolAssignment::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function hasAvailableSeats(): bool
    {
        return $this->seats_used < $this->seat_limit;
    }
}
