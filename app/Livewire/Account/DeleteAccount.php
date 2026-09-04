<?php

namespace App\Livewire\Account;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteAccount extends Component
{
    public $confirmName = '';

    public function deleteAccount()
    {
        $user = Auth::user();

        if ($this->confirmName !== $user->name) {
            $this->addError('confirmName', 'The name entered does not match your profile name.');
            return;
        }

        // Soft delete the user
        $userModel = User::findOrFail($user->id);
        $userModel->delete(); // Spatie SoftDeletes

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.account.delete-account')
            ->layout('layouts.app');
    }
}
