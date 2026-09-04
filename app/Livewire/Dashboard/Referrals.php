<?php

namespace App\Livewire\Dashboard;

use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Referrals extends Component
{
    public $referralCode = '';

    public function mount()
    {
        $this->referralCode = Auth::user()->referral_code;
    }

    public function render()
    {
        $referrals = Referral::where('referrer_id', Auth::id())
            ->with('referredUser')
            ->latest()
            ->get();

        return view('livewire.dashboard.referrals', compact('referrals'))
            ->layout('layouts.app');
    }
}
