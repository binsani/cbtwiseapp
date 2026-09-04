<?php

namespace App\Livewire\Exam;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Jobs\ExplainQuestionJob;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Results extends Component
{
    public $sessionId;
    public $examSession;
    public $breakdown = [];
    public $reviewQuestions = [];
    
    // AI explaining states
    public $explainingQuestionId = null;
    public $aiExplanation = null;

    public function mount($session)
    {
        $this->sessionId = $session;
        
        $this->examSession = ExamSession::where('user_id', Auth::id())
            ->where('status', 'submitted')
            ->findOrFail($session);
            
        $this->breakdown = $this->examSession->score_breakdown ?? [];
        
        // Eager load questions and user answers
        $this->loadReviewQuestions();
    }

    protected function loadReviewQuestions()
    {
        $answers = ExamAnswer::where('exam_session_id', $this->examSession->id)
            ->with('question')
            ->get();
            
        $this->reviewQuestions = $answers->map(fn($ans) => [
            'id' => $ans->question_id,
            'question_text' => $ans->question->question_text,
            'question_image' => $ans->question->question_image,
            'option_a' => $ans->question->option_a,
            'option_b' => $ans->question->option_b,
            'option_c' => $ans->question->option_c,
            'option_d' => $ans->question->option_d,
            'option_e' => $ans->question->option_e,
            'correct_option' => $ans->question->correct_option,
            'selected_option' => $ans->selected_option,
            'is_correct' => (bool) $ans->is_correct,
            'explanation' => $ans->question->explanation,
        ])->toArray();
    }

    /**
     * Determine WAEC grade based on percentage score.
     */
    public function getWaecGrade($percentage)
    {
        $grades = config('cbtwise.waec_grades', [
            'A1' => [75, 100],
            'B2' => [70, 74],
            'B3' => [65, 69],
            'C4' => [60, 64],
            'C5' => [55, 59],
            'C6' => [50, 54],
            'D7' => [45, 49],
            'E8' => [40, 44],
            'F9' => [0,  39],
        ]);

        foreach ($grades as $grade => $range) {
            if ($percentage >= $range[0] && $percentage <= $range[1]) {
                return $grade;
            }
        }

        return 'F9';
    }

    /**
     * Trigger OpenAI explanation for a specific question (Premium only).
     */
    public function getAiExplanation($questionId)
    {
        $user = Auth::user();
        if (!$user->isPremium()) {
            session()->flash('error', 'AI tutor explanations are exclusive to premium members. Please upgrade your plan!');
            return;
        }

        $question = Question::findOrFail($questionId);
        
        // If explanation already exists in cache/DB, return it immediately
        if ($question->explanation) {
            $this->aiExplanation = $question->explanation;
            $this->explainingQuestionId = $questionId;
            return;
        }

        // Dispatch background job to AI queue
        $this->explainingQuestionId = $questionId;
        $this->aiExplanation = 'Generating AI Tutor explanation... please wait.';

        // Dispatch background job (we'll create this job next)
        ExplainQuestionJob::dispatch($questionId, $user->id);
    }

    /**
     * Polled from client to check if AI explanation is ready in DB.
     */
    public function checkAiExplanationStatus()
    {
        if ($this->explainingQuestionId) {
            $question = Question::find($this->explainingQuestionId);
            if ($question && $question->explanation && $question->explanation !== 'Generating AI Tutor explanation... please wait.') {
                $this->aiExplanation = $question->explanation;
                
                // Refresh review list to show new explanation inline
                $this->loadReviewQuestions();
            }
        }
    }

    public function render()
    {
        return view('livewire.exam.results')
            ->layout('layouts.app');
    }
}
