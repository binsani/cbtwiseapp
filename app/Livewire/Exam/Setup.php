<?php

namespace App\Livewire\Exam;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Setup extends Component
{
    public $exams = [];
    public $subjects = [];
    public $years = [];

    // Form inputs
    public $selectedExamId = null;
    public $mode = 'practice'; // practice, mock, study
    public $selectedSubjects = [];
    public $year = 'random'; // 'random' or specific year integer
    public $questionCount = 20; // 10, 20, 40

    public $currentStep = 1;

    public function mount()
    {
        $this->exams = Cache::remember('active_exams', 3600, function () {
            return Exam::active()->get();
        });
        $this->years = config('cbtwise.exam_years', range(now()->year, 2000));
    }

    public function updatedSelectedExamId($examId)
    {
        $this->selectedSubjects = [];
        if ($examId) {
            $exam = Exam::find($examId);
            $this->subjects = Cache::remember("exam_subjects:{$examId}", 3600, function () use ($exam) {
                return $exam->subjects;
            });

            // Compulsory English for UTME
            if ($exam->slug === 'utme') {
                $english = $exam->subjects()->where('slug', 'english-language')->first();
                if ($english) {
                    $this->selectedSubjects[] = (string) $english->id;
                }
            }
        } else {
            $this->subjects = [];
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            if (!$this->selectedExamId) {
                $this->addError('selectedExamId', 'Please select an exam to proceed.');
                return;
            }
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            // Check premium limit for Mock mode
            if ($this->mode === 'mock' && !Auth::user()->isPremium()) {
                session()->flash('error', 'Mock exam mode is a premium feature. Please upgrade to practice full timed mock exams!');
                return;
            }
            $this->currentStep = 3;
        } elseif ($this->currentStep === 3) {
            $exam = Exam::find($this->selectedExamId);

            if ($exam->slug === 'utme') {
                if (count($this->selectedSubjects) !== 4) {
                    $this->addError('selectedSubjects', 'JAMB UTME requires exactly 4 subjects.');
                    return;
                }
                // Ensure English is selected
                $english = $exam->subjects()->where('slug', 'english-language')->first();
                if ($english && !in_array((string) $english->id, $this->selectedSubjects)) {
                    $this->addError('selectedSubjects', 'JAMB UTME requires English Language as a compulsory subject.');
                    return;
                }
            } else {
                // WAEC/NECO
                if (count($this->selectedSubjects) < 1 || count($this->selectedSubjects) > 9) {
                    $this->addError('selectedSubjects', 'Please select between 1 and 9 subjects.');
                    return;
                }
            }
            $this->currentStep = 4;
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function toggleSubject($subjectId)
    {
        $subjectId = (string) $subjectId;
        $exam = Exam::find($this->selectedExamId);

        if ($exam && $exam->slug === 'utme') {
            // Check if English
            $english = $exam->subjects()->where('slug', 'english-language')->first();
            if ($english && $english->id == $subjectId) {
                // Compulsory, cannot uncheck
                return;
            }
        }

        if (in_array($subjectId, $this->selectedSubjects)) {
            $this->selectedSubjects = array_filter($this->selectedSubjects, fn($id) => $id !== $subjectId);
        } else {
            if ($exam && $exam->slug === 'utme' && count($this->selectedSubjects) >= 4) {
                // Limit UTME to 4 subjects
                $this->addError('selectedSubjects', 'JAMB UTME limits you to exactly 4 subjects.');
                return;
            }
            $this->selectedSubjects[] = $subjectId;
        }
        $this->resetErrorBag('selectedSubjects');
    }

    public function startExam()
    {
        $user = Auth::user();

        // 1. Check daily question limit for free users
        if ($user->hasReachedDailyLimit()) {
            session()->flash('error', 'You have reached your free daily practice limit of ' . config('cbtwise.free_daily_limit', 20) . ' questions. Please upgrade to premium for unlimited practice.');
            return;
        }

        $exam = Exam::findOrFail($this->selectedExamId);
        $subjectCount = count($this->selectedSubjects);

        // Calculate question count and duration
        if ($this->mode === 'mock') {
            $questionsPerSubject = $exam->questions_per_subject_default ?? 40;
            $totalQuestions = $questionsPerSubject * $subjectCount;
            $durationSeconds = ($exam->duration_minutes_default ?? 120) * 60;
        } else {
            // Practice / Study modes
            $totalQuestions = $this->questionCount * $subjectCount;
            // 45 seconds per question for practice, or 1 minute. Let's make it 60 seconds per question.
            $durationSeconds = $totalQuestions * 60;
        }

        // Create the Exam Session
        $session = ExamSession::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'mode' => $this->mode,
            'subjects' => $this->selectedSubjects,
            'year' => $this->year === 'random' ? null : (int) $this->year,
            'total_questions' => $totalQuestions,
            'duration_seconds' => $durationSeconds,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        // Redirect to Exam Runner page
        return redirect()->route('exam.run', ['session' => $session->id]);
    }

    public function render()
    {
        return view('livewire.exam.setup')
            ->layout('layouts.app');
    }
}
