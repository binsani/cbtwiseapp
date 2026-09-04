<?php

namespace App\Livewire\Account;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $school;
    public $state;
    public $examYear;
    public $avatar;
    public $currentAvatarUrl;

    protected $rules = [
        'name' => 'required|string|max:100',
        'phone' => 'nullable|string|max:20',
        'school' => 'nullable|string|max:150',
        'state' => 'nullable|string|max:100',
        'examYear' => 'nullable|integer|min:2026|max:2035',
        'avatar' => 'nullable|image|max:1024', // max 1MB
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->school = $user->school;
        $this->state = $user->state;
        $this->examYear = $user->exam_year;
        $this->currentAvatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null;
    }

    public function updateProfile()
    {
        $this->validate();

        $user = User::findOrFail(Auth::id());
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->school = $this->school;
        $user->state = $this->state;
        $user->exam_year = $this->examYear;

        if ($this->avatar) {
            $path = $this->avatar->store('avatars', 'public');
            $user->avatar = $path;
            $this->currentAvatarUrl = asset('storage/' . $path);
        }

        $user->save();
        session()->flash('message', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.account.profile')
            ->layout('layouts.app');
    }
}
