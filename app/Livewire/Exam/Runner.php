<?php

namespace App\Livewire\Exam;

use App\Models\Bookmark;
use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\Subject;
use App\Services\QuestionFetcher;
use App\Services\ExamGradingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Runner extends Component
{
    public $sessionId;
    public $mode;
    public $topicId = null;
    public $topicName = null;
    public bool $topicPracticeUsesSubjectFallback = false;
    
    // Active Navigation state
    public $selectedSubjectId;
    public $currentIndex = 0; // index of the active question in the current subject
    
    // Light-weight state arrays
    public $answers = []; // question_id => selected_option
    public $flagged = []; // question_id => boolean
    public $bookmarked = []; // question_id => boolean
    
    public $timeRemaining;
    
    // Subjects config
    public $subjectList = []; // Array of subjects info: [['id' => 1, 'name' => 'English', 'icon' => '...']]
    
    public function mount($session)
    {
        $this->sessionId = $session;
        
        $examSession = ExamSession::where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->find($session);

        if (!$examSession) {
            $submitted = ExamSession::where('user_id', Auth::id())
                ->where('status', 'submitted')
                ->find($session);

            if ($submitted) {
                return $this->redirectRoute('exam.results', ['session' => $session]);
            }

            session()->flash('error', 'Exam session not found or already completed. Please configure a new session.');
            return $this->redirectRoute('exam.setup');
        }
            
        $this->mode = $examSession->mode;
        $this->topicId = $examSession->topic_id;
        $this->topicName = $examSession->topic?->name;
        
        // Calculate remaining seconds
        $this->timeRemaining = $examSession->remainingSeconds();
        
        if ($this->timeRemaining <= 0) {
            $this->autoSubmit();
            return;
        }
        
        // Fetch subjects
        $subjectIds = $examSession->subjects;
        $subjects = Subject::whereIn('id', $subjectIds)->get()->sortBy(function($subject) use ($subjectIds) {
            return array_search($subject->id, $subjectIds);
        });
        
        $this->subjectList = $subjects->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'icon' => $s->icon,
        ])->toArray();
        
        $this->selectedSubjectId = $this->subjectList[0]['id'] ?? null;
        
        // Check if questions are already generated for this session
        $existingAnswersCount = ExamAnswer::where('exam_session_id', $examSession->id)->count();
        
        if ($existingAnswersCount === 0) {
            // Generate questions for each subject
            $questionFetcher = app(QuestionFetcher::class);
            $exam = $examSession->exam;
            
            $defaultQuestionsPerSubject = $examSession->mode === 'mock'
                ? ($exam->questions_per_subject_default ?? 40)
                : (int) ($examSession->total_questions / count($subjectIds));
                
            $totalGenerated = 0;
            
            foreach ($subjects as $subject) {
                $questionsForSubject = $examSession->mode === 'mock' && $exam->slug === 'utme'
                    ? ($subject->slug === 'english-language' ? 60 : 40)
                    : $defaultQuestionsPerSubject;
                $questions = $questionFetcher->fetch($exam, $subject, $questionsForSubject, $examSession->year, $examSession->topic_id);
                $this->topicPracticeUsesSubjectFallback = $this->topicPracticeUsesSubjectFallback
                    || $questionFetcher->lastFetchUsedSubjectFallback;
                
                foreach ($questions as $q) {
                    ExamAnswer::create([
                        'exam_session_id' => $examSession->id,
                        'question_id' => $q->id,
                        'selected_option' => null,
                        'is_correct' => false,
                        'time_spent_seconds' => 0,
                        'flagged_for_review' => false,
                    ]);
                    
                    // Increment served counter
                    $q->incrementServed();
                }
                
                $totalGenerated += $questions->count();
            }
            
            // Adjust session total questions
            $examSession->update(['total_questions' => $totalGenerated]);
            
            // Increment free user daily count
            $user = Auth::user();
            if ($user->isFree()) {
                $user->incrementDailyCount($totalGenerated);
            }
        }
        
        // Load answers, flagged status, and bookmarks
        $sessionAnswers = ExamAnswer::where('exam_session_id', $examSession->id)->get();
        foreach ($sessionAnswers as $ans) {
            $this->answers[$ans->question_id] = $ans->selected_option;
            $this->flagged[$ans->question_id] = (bool) $ans->flagged_for_review;
        }

        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->whereIn('question_id', $sessionAnswers->pluck('question_id'))
            ->pluck('question_id')
            ->toArray();
        foreach ($bookmarks as $bId) {
            $this->bookmarked[$bId] = true;
        }
    }
    
    /**
     * Computed Questions list for the selected subject.
     */
    public function getQuestionsProperty()
    {
        $examSession = ExamSession::find($this->sessionId);
        
        if (!$examSession) return collect();
        
        // Keep a stable question order for the whole session. A whereIn query
        // has no ordering guarantee and could otherwise make a question appear
        // under a different navigator number after a Livewire refresh.
        return Question::query()
            ->select('questions.*')
            ->with('topic')
            ->join('exam_answers', 'exam_answers.question_id', '=', 'questions.id')
            ->where('exam_answers.exam_session_id', $this->sessionId)
            ->where('subject_id', $this->selectedSubjectId)
            ->orderBy('exam_answers.id')
            ->get();
    }
    
    public function selectSubject($subjectId)
    {
        if (!in_array((int) $subjectId, array_column($this->subjectList, 'id'), true)) {
            return;
        }

        $this->selectedSubjectId = $subjectId;
        $this->currentIndex = 0;
    }
    
    public function selectOption($questionId, $option)
    {
        $option = strtolower((string) $option);
        if (!in_array($option, ['a', 'b', 'c', 'd', 'e'], true)) {
            return;
        }

        $answer = ExamAnswer::where('exam_session_id', $this->sessionId)
            ->where('question_id', $questionId)
            ->first();

        // Only questions actually assigned to this student's session can be
        // answered. Do not let a manipulated browser request affect UI state.
        if (!$answer) {
            return;
        }

        $this->answers[$questionId] = $option;

        $question = $answer->question;
        $isCorrect = $question && strtolower((string) $question->correct_option) === $option;

        $answer->update([
            'selected_option' => $option,
            'is_correct' => $isCorrect,
        ]);
    }
    
    public function toggleFlag($questionId)
    {
        $isFlagged = !($this->flagged[$questionId] ?? false);
        $this->flagged[$questionId] = $isFlagged;
        
        ExamAnswer::where('exam_session_id', $this->sessionId)
            ->where('question_id', $questionId)
            ->update(['flagged_for_review' => $isFlagged]);
    }

    public function toggleBookmark($questionId)
    {
        if (!ExamAnswer::where('exam_session_id', $this->sessionId)->where('question_id', $questionId)->exists()) {
            return;
        }

        $userId = Auth::id();
        $isBookmarked = !($this->bookmarked[$questionId] ?? false);
        $this->bookmarked[$questionId] = $isBookmarked;

        if ($isBookmarked) {
            Bookmark::firstOrCreate([
                'user_id' => $userId,
                'question_id' => $questionId,
            ]);
        } else {
            Bookmark::where('user_id', $userId)
                ->where('question_id', $questionId)
                ->delete();
        }
    }
    
    public function navigate($index)
    {
        $max = max(0, $this->questions->count() - 1);
        $this->currentIndex = max(0, min((int) $index, $max));
    }
    
    public function syncTimer($remainingSeconds = null)
    {
        // Time is derived on the server. The browser may pause, be throttled,
        // or be modified; it must never be able to add exam time.
        $examSession = ExamSession::where('id', $this->sessionId)
            ->where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->first();

        if (!$examSession) {
            return;
        }

        $this->timeRemaining = $examSession->remainingSeconds();
        
        // Auto submit if no time left
        if ($this->timeRemaining <= 0) {
            $this->autoSubmit();
        }
    }
    
    public function submit()
    {
        $examSession = ExamSession::where('user_id', Auth::id())
            ->find($this->sessionId);
            
        if (!$examSession) {
            return redirect()->route('dashboard');
        }

        // If session is already finalized, redirect directly to results without regrading
        if ($examSession->status === 'submitted') {
            return redirect()->route('exam.results', ['session' => $examSession->id]);
        }

        // Atomically grade session, update stats, and eliminate N+1 queries
        app(ExamGradingService::class)->grade($examSession);

        return redirect()->route('exam.results', ['session' => $examSession->id]);
    }
    
    public function autoSubmit()
    {
        $this->submit();
    }
    
    public function render()
    {
        $questions = $this->questions;
        $activeQuestion = $questions->get($this->currentIndex);
        
        return view('livewire.exam.runner', [
            'questionsList' => $questions,
            'activeQuestion' => $activeQuestion,
        ])->layout('layouts.app');
    }
}
