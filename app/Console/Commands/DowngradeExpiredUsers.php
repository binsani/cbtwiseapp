<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('cbtwise:downgrade-expired')]
#[Description('Downgrade users with expired premium subscriptions and send warnings 3 days before.')]
class DowngradeExpiredUsers extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Downgrade expired users
        $downgraded = \App\Models\User::where('plan', 'premium')
            ->whereNotNull('premium_expires_at')
            ->where('premium_expires_at', '<', now())
            ->update(['plan' => 'free']);

        $this->info("Downgraded {$downgraded} expired premium users to free.");

        // 2. Warn users expiring in 3 days
        $expiringUsers = \App\Models\User::where('plan', 'premium')
            ->whereNotNull('premium_expires_at')
            ->whereDate('premium_expires_at', now()->addDays(3)->toDateString())
            ->get();

        foreach ($expiringUsers as $user) {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\PremiumExpiringMail($user));
        }

        $this->info("Sent expiration warnings to " . $expiringUsers->count() . " users.");
    }
}
