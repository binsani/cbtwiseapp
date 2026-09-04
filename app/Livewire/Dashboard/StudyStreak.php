<?php

namespace App\Livewire\Dashboard;

use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudyStreak extends Component
{
    public $streakDays = 0;
    public $freezeTokens = 0;
    public $activeDates = [];
    public $milestones = [];

    public function mount()
    {
        $user = Auth::user();
        $this->streakDays = $user->study_streak_days;
        $this->freezeTokens = $user->streak_freeze_tokens;

        // 1. Fetch unique active dates in the last 30 days
        $sessions = ExamSession::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->where('submitted_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(submitted_at) as active_date')
            ->groupBy('active_date')
            ->pluck('active_date')
            ->toArray();

        $this->activeDates = array_map(fn($d) => date('Y-m-d', strtotime($d)), $sessions);

        // 2. Generate list of milestones
        $this->milestones = [
            ['days' => 3, 'label' => 'Bronze Badge', 'achieved' => $this->streakDays >= 3],
            ['days' => 7, 'label' => 'Silver Medal', 'achieved' => $this->streakDays >= 7],
            ['days' => 15, 'label' => 'Gold Crown', 'achieved' => $this->streakDays >= 15],
            ['days' => 30, 'label' => 'CBT Champion', 'achieved' => $this->streakDays >= 30],
        ];
    }

    public function purchaseFreezeToken()
    {
        $user = User::findOrFail(Auth::id());

        // Gamified purchase: check if they have completed at least 5 exams to buy 1 token
        $totalExams = ExamSession::where('user_id', $user->id)->where('status', 'submitted')->count();
        $tokensBought = $user->examSessions()->count() / 5; // mock cost

        if ($totalExams < 5) {
            session()->flash('error', 'You must complete at least 5 CBT exam sessions to earn enough points for a Streak Freeze!');
            return;
        }

        // Increment tokens
        $user->increment('streak_freeze_tokens');
        $this->freezeTokens = $user->streak_freeze_tokens;

        session()->flash('success', 'Streak Freeze token acquired successfully!');
    }

    public function render()
    {
        return view('livewire.dashboard.study-streak')
            ->layout('layouts.app');
    }
}
