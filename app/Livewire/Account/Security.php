<?php

namespace App\Livewire\Account;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Security extends Component
{
    public $currentPassword = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';

    protected $rules = [
        'currentPassword' => 'required',
        'newPassword' => 'required|min:8',
        'newPasswordConfirmation' => 'required|same:newPassword',
    ];

    public function updatePassword()
    {
        $this->validate();

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Your current password is incorrect.');
            return;
        }

        $user->password = Hash::make($this->newPassword);
        $user->save();

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
        session()->flash('message', 'Password updated successfully!');
    }

    public function render()
    {
        return view('livewire.account.security')
            ->layout('layouts.app');
    }
}
