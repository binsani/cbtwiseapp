<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Subscription extends Component
{
    public $planName = 'Free';
    public $expiresAt = null;

    public function mount()
    {
        $user = Auth::user();
        $this->planName = $user->isPremium() ? 'Premium' : 'Free';
        $this->expiresAt = $user->premium_expires_at ? $user->premium_expires_at->format('M d, Y') : null;
    }

    public function render()
    {
        return view('livewire.account.subscription')
            ->layout('layouts.app');
    }
}
