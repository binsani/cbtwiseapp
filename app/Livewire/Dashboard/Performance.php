<?php

namespace App\Livewire\Dashboard;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Performance extends Component
{
    public $subjectAccuracy = [];
    public $dailyTrend = [];
    public $totalSessions = 0;
    public $overallAccuracy = 0;

    public function mount()
    {
        $userId = Auth::id();

        // 1. Total Sessions
        $this->totalSessions = ExamSession::where('user_id', $userId)
            ->where('status', 'submitted')
            ->count();

        // 2. Accuracy per Subject
        $answers = ExamAnswer::whereHas('session', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('status', 'submitted');
        })
        ->with('question.subject')
        ->get();

        $subjectGroups = [];
        $totalCorrect = 0;
        $totalAnswered = 0;

        foreach ($answers as $ans) {
            if (!$ans->question || !$ans->question->subject) {
                continue;
            }

            $subjectName = $ans->question->subject->name;
            if (!isset($subjectGroups[$subjectName])) {
                $subjectGroups[$subjectName] = ['correct' => 0, 'total' => 0];
            }

            $subjectGroups[$subjectName]['total']++;
            $totalAnswered++;

            if ($ans->is_correct) {
                $subjectGroups[$subjectName]['correct']++;
                $totalCorrect++;
            }
        }

        foreach ($subjectGroups as $name => $stats) {
            $this->subjectAccuracy[] = [
                'subject' => $name,
                'correct' => $stats['correct'],
                'total' => $stats['total'],
                'accuracy' => $stats['total'] > 0 ? round(($stats['correct'] / $stats['total']) * 100, 1) : 0,
            ];
        }

        // Sort by accuracy descending
        usort($this->subjectAccuracy, fn($a, $b) => $b['accuracy'] <=> $a['accuracy']);

        $this->overallAccuracy = $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100, 1) : 0;

        // 3. Time Trend (Daily)
        $dailySessions = ExamSession::where('user_id', $userId)
            ->where('status', 'submitted')
            ->selectRaw("DATE(submitted_at) as session_date, SUM(correct_count) as daily_correct, SUM(total_questions) as daily_total")
            ->groupBy('session_date')
            ->orderBy('session_date', 'asc')
            ->take(15)
            ->get();

        foreach ($dailySessions as $session) {
            $total = (int)$session->daily_total;
            $correct = (int)$session->daily_correct;
            $this->dailyTrend[] = [
                'date' => date('M d', strtotime($session->session_date)),
                'accuracy' => $total > 0 ? round(($correct / $total) * 100, 1) : 0,
            ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard.performance')
            ->layout('layouts.app');
    }
}
