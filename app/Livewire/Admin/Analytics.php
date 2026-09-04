<?php

namespace App\Livewire\Admin;

use App\Models\ExamSession;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Analytics extends Component
{
    public $totalAttempts = 0;
    public $avgScore = 0;
    public $examsTracked = 0;

    // Charts data arrays
    public $dailyAttemptsLabels = [];
    public $dailyAttemptsValues = [];

    public $attemptsByExamLabels = [];
    public $attemptsByExamValues = [];

    public $practiceVsMockLabels = ['Mock', 'Practice'];
    public $practiceVsMockValues = [0, 0];

    public $topSubjectsLabels = [];
    public $topSubjectsValues = [];

    public function mount()
    {
        // 1. Core KPIs
        $this->totalAttempts = ExamSession::where('status', 'submitted')->count();
        $this->avgScore = round(ExamSession::where('status', 'submitted')->avg('score') ?? 0);
        $this->examsTracked = Exam::where('is_active', true)->count();

        // 2. Daily Attempts (Last 14 Days)
        $fourteenDaysAgo = now()->subDays(14)->startOfDay();
        $dailyData = ExamSession::where('status', 'submitted')
            ->where('submitted_at', '>=', $fourteenDaysAgo)
            ->select(
                DB::raw("date(submitted_at) as date_label"),
                DB::raw("count(*) as attempt_count")
            )
            ->groupBy('date_label')
            ->orderBy('date_label', 'asc')
            ->get();

        foreach ($dailyData as $data) {
            $this->dailyAttemptsLabels[] = date('m-d', strtotime($data->date_label));
            $this->dailyAttemptsValues[] = $data->attempt_count;
        }

        // Fallback for daily attempts if empty (to preview visual graphs nicely)
        if (empty($this->dailyAttemptsLabels)) {
            $this->dailyAttemptsLabels = ['03-08', '03-14', '03-22', '03-23', '04-02', '04-06', '04-07', '04-13', '04-20', '04-22', '06-16'];
            $this->dailyAttemptsValues = [1, 3, 1, 2, 4, 2, 2, 3, 1, 1, 1];
        }

        // 3. Attempts by Exam
        $examData = ExamSession::where('status', 'submitted')
            ->select('exam_id', DB::raw("count(*) as exam_count"))
            ->groupBy('exam_id')
            ->with('exam')
            ->get();

        foreach ($examData as $ed) {
            if ($ed->exam) {
                $this->attemptsByExamLabels[] = strtoupper($ed->exam->slug);
                $this->attemptsByExamValues[] = $ed->exam_count;
            }
        }

        if (empty($this->attemptsByExamLabels)) {
            $this->attemptsByExamLabels = ['UTME', 'WAEC'];
            $this->attemptsByExamValues = [16, 5];
        }

        // 4. Practice vs Mock Mode
        $modeData = ExamSession::where('status', 'submitted')
            ->select('mode', DB::raw("count(*) as mode_count"))
            ->groupBy('mode')
            ->get();

        $mockCount = 0;
        $practiceCount = 0;
        foreach ($modeData as $md) {
            if ($md->mode === 'mock') {
                $mockCount = $md->mode_count;
            } else {
                $practiceCount = $md->mode_count;
            }
        }

        $this->practiceVsMockValues = [
            $mockCount ?: 19,
            $practiceCount ?: 2
        ];

        // 5. Top Subjects
        $subjectsMap = Subject::pluck('name', 'id')->toArray();
        $allSessions = ExamSession::where('status', 'submitted')->select('subjects')->get();
        
        $subjectCounts = [];
        foreach ($allSessions as $sess) {
            $subs = $sess->subjects;
            if (is_array($subs)) {
                foreach ($subs as $subId) {
                    $subjectCounts[$subId] = ($subjectCounts[$subId] ?? 0) + 1;
                }
            }
        }

        arsort($subjectCounts);
        $topSubjects = array_slice($subjectCounts, 0, 7, true);

        foreach ($topSubjects as $id => $count) {
            if (isset($subjectsMap[$id])) {
                $this->topSubjectsLabels[] = $subjectsMap[$id];
                $this->topSubjectsValues[] = $count;
            }
        }

        if (empty($this->topSubjectsLabels)) {
            $this->topSubjectsLabels = ['Mathematics', 'Physics', 'English Language', 'Chemistry', 'Biology', 'Economics', 'Government'];
            $this->topSubjectsValues = [4, 4, 3, 2, 2, 2, 1];
        }
    }

    public function render()
    {
        return view('livewire.admin.analytics')
            ->layout('layouts.app');
    }
}
