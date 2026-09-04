<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Leaderboard extends Component
{
    public $isOptedIn = true;

    public function mount()
    {
        $this->isOptedIn = (bool) Auth::user()->is_leaderboard_visible;
    }

    public function toggleOptIn()
    {
        $user = User::findOrFail(Auth::id());
        $user->is_leaderboard_visible = !$user->is_leaderboard_visible;
        $user->save();

        $this->isOptedIn = $user->is_leaderboard_visible;
        session()->flash('message', $this->isOptedIn ? 'You are now visible on the leaderboard.' : 'You have opted out of the leaderboard.');
    }

    public function render()
    {
        // Fetch top 10 users by study streak who opted in
        $leaders = User::where('is_leaderboard_visible', true)
            ->where('study_streak_days', '>', 0)
            ->orderBy('study_streak_days', 'desc')
            ->take(10)
            ->get();

        return view('livewire.dashboard.leaderboard', compact('leaders'))
            ->layout('layouts.app');
    }
}
