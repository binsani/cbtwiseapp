<?php

namespace App\Livewire\Account;

use App\Models\PurchaseCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchaseCodes extends Component
{
    public $code = '';

    public function redeem()
    {
        $this->validate([
            'code' => 'required|string|max:32',
        ]);

        $voucher = PurchaseCode::where('code', $this->code)
            ->where('is_active', true)
            ->whereNull('used_by_user_id')
            ->first();

        if (!$voucher) {
            $this->addError('code', 'This voucher code is invalid, inactive, or already used.');
            return;
        }

        // Apply voucher to user subscription
        $user = User::findOrFail(Auth::id());
        $user->plan = 'premium';
        
        $daysToAdd = (int) $voucher->duration_days;
        $currentExpiry = $user->premium_expires_at;
        
        if ($currentExpiry && $currentExpiry->isFuture()) {
            $user->premium_expires_at = $currentExpiry->addDays($daysToAdd);
        } else {
            $user->premium_expires_at = now()->addDays($daysToAdd);
        }

        $user->save();

        // Mark code as used
        $voucher->used_by_user_id = $user->id;
        $voucher->used_at = now();
        $voucher->is_active = false;
        $voucher->save();

        $this->code = '';
        session()->flash('message', "Successfully activated premium plan for {$daysToAdd} days!");
    }

    public function render()
    {
        $history = PurchaseCode::where('used_by_user_id', Auth::id())
            ->latest('used_at')
            ->get();

        return view('livewire.account.purchase-codes', compact('history'))
            ->layout('layouts.app');
    }
}
