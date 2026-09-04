<?php

namespace App\Livewire;

use App\Models\ExamSession;
use App\Models\ExamAnswer;
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
        
        // 1. Calculate general stats
        $sessions = ExamSession::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->get();
            
        $sessionIds = $sessions->pluck('id');
        
        $answers = ExamAnswer::whereIn('exam_session_id', $sessionIds)->get();
        
        $this->totalAnswered = $answers->whereNotNull('selected_option')->count();
        $correct = $answers->where('is_correct', true)->count();
        
        $this->accuracy = $this->totalAnswered > 0 ? round(($correct / $this->totalAnswered) * 100, 1) : 0;
        
        // 2. Recent Sessions
        $this->recentSessions = ExamSession::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->with('exam')
            ->latest('submitted_at')
            ->take(5)
            ->get();
            
        // 3. Subject Performance for Chart.js
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
        
        // Sort subjects to find strong/weak
        asort($this->subjectPerformance);
        
        // 4. Study Plan Cache check
        $this->studyPlan = Cache::get("study-plan:{$user->id}");
        $this->studyPlanStatus = Cache::get("study-plan-status:{$user->id}");
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
