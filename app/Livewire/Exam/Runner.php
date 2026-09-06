<?php

namespace App\Livewire\Exam;

use App\Models\Bookmark;
use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\Subject;
use App\Services\QuestionFetcher;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Runner extends Component
{
    public $sessionId;
    public $mode;
    
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
        
        // Calculate remaining seconds
        $elapsed = now()->diffInSeconds($examSession->started_at);
        $this->timeRemaining = max(0, $examSession->duration_seconds - $elapsed);
        
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
            
            $questionsPerSubject = $examSession->mode === 'mock' 
                ? ($exam->questions_per_subject_default ?? 40)
                : (int) ($examSession->total_questions / count($subjectIds));
                
            $totalGenerated = 0;
            
            foreach ($subjects as $subject) {
                $questions = $questionFetcher->fetch($exam, $subject, $questionsPerSubject, $examSession->year);
                
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
        
        return Question::whereIn('id', function($query) {
                $query->select('question_id')
                    ->from('exam_answers')
                    ->where('exam_session_id', $this->sessionId);
            })
            ->where('subject_id', $this->selectedSubjectId)
            ->get();
    }
    
    public function selectSubject($subjectId)
    {
        $this->selectedSubjectId = $subjectId;
        $this->currentIndex = 0;
    }
    
    public function selectOption($questionId, $option)
    {
        $this->answers[$questionId] = $option;
        
        // Save to DB in the background
        $answer = ExamAnswer::where('exam_session_id', $this->sessionId)
            ->where('question_id', $questionId)
            ->first();
            
        if ($answer) {
            $question = Question::find($questionId);
            $isCorrect = $question && $question->correct_option === $option;
            
            $answer->update([
                'selected_option' => $option,
                'is_correct' => $isCorrect,
            ]);
        }
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
        $max = $this->questions->count() - 1;
        $this->currentIndex = max(0, min($index, $max));
    }
    
    public function syncTimer($remainingSeconds)
    {
        $this->timeRemaining = (int) $remainingSeconds;
        
        // Auto submit if no time left
        if ($this->timeRemaining <= 0) {
            $this->autoSubmit();
        }
    }
    
    public function submit()
    {
        $examSession = ExamSession::where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->find($this->sessionId);
            
        if (!$examSession) {
            return redirect()->route('dashboard');
        }
        
        // 1. Grade the exam
        $answers = ExamAnswer::where('exam_session_id', $examSession->id)->get();
        $totalQuestions = $answers->count();
        $correctCount = 0;
        
        $subjectCorrects = [];
        $subjectTotals = [];
        
        foreach ($answers as $ans) {
            // Check correctness again to be absolute
            $question = Question::find($ans->question_id);
            $isCorrect = $question && $question->correct_option === $ans->selected_option;
            
            if ($isCorrect) {
                $correctCount++;
                $ans->update(['is_correct' => true]);
                $question->incrementCorrect();
            } else {
                $ans->update(['is_correct' => false]);
            }
            
            // subject-wise breakdown
            $subId = $question->subject_id;
            $subjectTotals[$subId] = ($subjectTotals[$subId] ?? 0) + 1;
            if ($isCorrect) {
                $subjectCorrects[$subId] = ($subjectCorrects[$subId] ?? 0) + 1;
            }
        }
        
        // Build score breakdown
        $breakdown = [];
        foreach ($subjectTotals as $subId => $total) {
            $correct = $subjectCorrects[$subId] ?? 0;
            $subModel = Subject::find($subId);
            $breakdown[$subId] = [
                'subject_name' => $subModel->name,
                'correct' => $correct,
                'total' => $total,
                'percentage' => $total > 0 ? round(($correct / $total) * 100, 2) : 0,
            ];
        }
        
        // 2. Score scale
        $exam = $examSession->exam;
        $score = 0;
        
        if ($exam->slug === 'utme') {
            // Scaled to 400
            $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 400, 2) : 0;
        } else {
            // WAEC/NECO is simple average percent
            $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
        }
        
        // Update ExamSession
        $examSession->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'correct_count' => $correctCount,
            'score' => $score,
            'score_breakdown' => $breakdown,
        ]);
        
        // Update user study streak
        Auth::user()->updateStreak();
        
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
