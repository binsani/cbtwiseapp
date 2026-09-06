<?php

namespace App\Livewire;

use App\Models\Bookmark;
use App\Models\ExamSession;
use App\Models\ExamAnswer;
use App\Models\Subject;
use App\Models\User;
use App\Jobs\StudyPlanJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class UserDashboard extends Component
{
    public $streakDays = 0;
    public $totalAnswered = 0;
    public $accuracy = 0;
    
    // New Feature States
    public $todayAnswered = 0;
    public $dailyGoal = 20;
    public $activeSession = null;
    public $bookmarkCount = 0;
    public $leaderboardRank = null;
    public $weakestSubject = null;
    public $strongestSubject = null;
    public $referralCode = '';

    public $recentSessions = [];
    public $subjectPerformance = []; // subject name => accuracy %
    
    public $studyPlan = null;
    public $studyPlanStatus = null; // null, generating, ready, failed, no_data, rate_limited

    public function mount()
    {
        $user = Auth::user();
        
        // Update user streak if they active today
        $user->updateStreak();
        
        $this->streakDays = $user->study_streak_days;
        $this->referralCode = $user->referral_code;
        
        // 1. Calculate general stats
        $sessions = ExamSession::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->get();
            
        $sessionIds = $sessions->pluck('id');
        
        $this->totalAnswered = ExamAnswer::whereIn('exam_session_id', $sessionIds)
            ->whereNotNull('selected_option')
            ->count();
            
        $correct = ExamAnswer::whereIn('exam_session_id', $sessionIds)
            ->where('is_correct', true)
            ->count();
        
        $this->accuracy = $this->totalAnswered > 0 ? round(($correct / $this->totalAnswered) * 100, 1) : 0;

        // 2. Daily Goal & Activity
        $this->dailyGoal = config('cbtwise.free_daily_limit', 20);
        $this->todayAnswered = ExamAnswer::whereIn('exam_session_id', $sessionIds)
            ->whereDate('updated_at', today())
            ->whereNotNull('selected_option')
            ->count();

        // 3. Active in-progress session
        $this->activeSession = ExamSession::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->with('exam')
            ->latest('started_at')
            ->first();

        // 4. Bookmarks Count
        $this->bookmarkCount = Bookmark::where('user_id', $user->id)->count();

        // 5. Leaderboard Rank Snapshot
        if ($user->study_streak_days > 0) {
            $this->leaderboardRank = User::where('is_leaderboard_visible', true)
                ->where('study_streak_days', '>', $user->study_streak_days)
                ->count() + 1;
        } else {
            $this->leaderboardRank = null;
        }
        
        // 6. Recent Sessions
        $this->recentSessions = ExamSession::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->with('exam')
            ->latest('submitted_at')
            ->take(5)
            ->get();
            
        // 7. Subject Performance for Chart.js
        $subAnswers = ExamAnswer::whereIn('exam_session_id', $sessionIds)
            ->join('questions', 'exam_answers.question_id', '=', 'questions.id')
            ->join('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->select('subjects.name as subject_name', 'exam_answers.is_correct')
            ->get();
            
        $grouped = $subAnswers->groupBy('subject_name');
        
        foreach ($grouped as $name => $group) {
            $total = $group->count();
            $corr = $group->where('is_correct', true)->count();
            $this->subjectPerformance[$name] = round(($corr / $total) * 100, 1);
        }
        
        // Sort subjects ascending to identify weak vs strong
        asort($this->subjectPerformance);

        if (!empty($this->subjectPerformance)) {
            $subNames = array_keys($this->subjectPerformance);
            $weakestName = reset($subNames);
            $strongestName = end($subNames);
            
            $weakestSub = Subject::where('name', $weakestName)->first();
            $this->weakestSubject = [
                'name' => $weakestName,
                'accuracy' => $this->subjectPerformance[$weakestName],
                'id' => $weakestSub?->id,
            ];
            $this->strongestSubject = [
                'name' => $strongestName,
                'accuracy' => $this->subjectPerformance[$strongestName],
            ];
        }
        
        // 8. Study Plan Cache check
        $this->studyPlan = Cache::get("study-plan:{$user->id}");
        $this->studyPlanStatus = Cache::get("study-plan-status:{$user->id}");
    }

    public function cancelActiveSession()
    {
        if ($this->activeSession) {
            $this->activeSession->delete();
            $this->activeSession = null;
            session()->flash('message', 'Incomplete exam session has been discarded.');
        }
    }

    public function generateStudyPlan()
    {
        $user = Auth::user();

        // Check premium status
        if (!$user->isPremium()) {
            session()->flash('error', 'AI Study Plans are exclusive to premium members. Please upgrade your plan!');
            return;
        }

        // Require at least 3 practice sessions to generate a study plan
        $submittedSessionsCount = ExamSession::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->count();

        if ($submittedSessionsCount < 3) {
            session()->flash('error', 'You need to complete at least 3 practice sessions before we can analyze and generate your study plan.');
            return;
        }

        // Rate limit check
        $rateLimitKey = 'ai-rate-limit:' . $user->id;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            session()->flash('error', "You have reached the limit of 10 AI requests per hour. Please try again in " . ceil($seconds / 60) . " minutes.");
            return;
        }

        // Dispatch background job to AI queue
        $this->studyPlanStatus = 'generating';
        Cache::put("study-plan-status:{$user->id}", 'generating', 600);

        StudyPlanJob::dispatch($user->id);

        session()->flash('message', 'AI Study Coach is analyzing your performance. Your study plan will be ready in a few moments.');
    }

    /**
     * Polled from client to check if AI study plan is ready.
     */
    public function checkStudyPlanStatus()
    {
        $user = Auth::user();
        $this->studyPlanStatus = Cache::get("study-plan-status:{$user->id}");
        if ($this->studyPlanStatus === 'ready') {
            $this->studyPlan = Cache::get("study-plan:{$user->id}");
        }
    }

    public function render()
    {
        return view('livewire.user-dashboard')
            ->layout('layouts.app');
    }
}
