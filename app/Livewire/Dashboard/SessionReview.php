<?php

namespace App\Livewire\Dashboard;

use App\Models\Bookmark;
use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Jobs\ExplainQuestionJob;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SessionReview extends Component
{
    public $sessionId;
    public $examSession;
    public $breakdown = [];
    public $reviewQuestions = [];
    public $bookmarkedQuestionIds = [];

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

        $this->loadReviewQuestions();
        $this->loadBookmarks();
    }

    protected function loadBookmarks()
    {
        $questionIds = collect($this->reviewQuestions)->pluck('id');
        $this->bookmarkedQuestionIds = Bookmark::where('user_id', Auth::id())
            ->whereIn('question_id', $questionIds)
            ->pluck('question_id')
            ->toArray();
    }

    public function toggleBookmark($questionId)
    {
        $userId = Auth::id();
        $existing = Bookmark::where('user_id', $userId)->where('question_id', $questionId)->first();

        if ($existing) {
            $existing->delete();
            $this->bookmarkedQuestionIds = array_values(array_diff($this->bookmarkedQuestionIds, [$questionId]));
            session()->flash('message', 'Question removed from bookmarks.');
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'question_id' => $questionId,
            ]);
            $this->bookmarkedQuestionIds[] = $questionId;
            session()->flash('message', 'Question saved to bookmarks!');
        }
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
            'is_correct' => (bool)$ans->is_correct,
            'explanation' => $ans->question->explanation,
        ])->toArray();
    }

    public function getAiExplanation($questionId)
    {
        $user = Auth::user();
        if (!$user->isPremium()) {
            session()->flash('error', 'AI tutor explanations are exclusive to premium members. Please upgrade your plan!');
            return;
        }

        $question = Question::findOrFail($questionId);

        if ($question->explanation) {
            $this->aiExplanation = $question->explanation;
            $this->explainingQuestionId = $questionId;
            return;
        }

        $this->explainingQuestionId = $questionId;
        $this->aiExplanation = 'Generating AI Tutor explanation... please wait.';

        ExplainQuestionJob::dispatch($questionId, $user->id);
    }

    public function checkAiExplanationStatus()
    {
        if ($this->explainingQuestionId) {
            $question = Question::find($this->explainingQuestionId);
            if ($question && $question->explanation && $question->explanation !== 'Generating AI Tutor explanation... please wait.') {
                $this->aiExplanation = $question->explanation;
                $this->loadReviewQuestions();
            }
        }
    }

    public function render()
    {
        return view('livewire.dashboard.session-review')
            ->layout('layouts.app');
    }
}
