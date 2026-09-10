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
    // Form inputs
    public $selectedExamId = null;
    public $mode = 'practice'; // practice, mock, study
    public $selectedSubjects = [];
    public $year = 'random'; // 'random' or specific year integer
    public $questionCount = 20; // 10, 20, 40
    public $selectedCourse = '';
    public $selectedTopicId = null;

    public $currentStep = 1;

    public function mount($exam = null, $course = null, $mode = null, $subject = null, $topic = null)
    {
        Cache::forget('active_exams');

        // Support query parameters from dashboard quick links: ?exam=utme, ?mode=mock, ?subject=3, ?course=medicine-and-surgery, ?topic=12
        $examParam = $exam ?? request('exam');
        if ($examParam) {
            $foundExam = is_numeric($examParam) ? Exam::find($examParam) : Exam::where('slug', $examParam)->first();
            if ($foundExam) {
                $this->selectedExamId = $foundExam->id;
                $this->updatedSelectedExamId($foundExam->id);
                $this->currentStep = 2;
            }
        }

        $modeParam = $mode ?? request('mode');
        if (in_array($modeParam, ['practice', 'mock', 'study'])) {
            $this->mode = $modeParam;
        }

        // If direct mock exam route or ?mode=mock
        if (request()->routeIs('mock-exams') || $modeParam === 'mock') {
            $this->mode = 'mock';
        }

        $subjectParam = $subject ?? request('subject');
        if ($subjectParam) {
            $foundSubject = is_numeric($subjectParam) ? Subject::find($subjectParam) : Subject::where('slug', $subjectParam)->first();
            if ($foundSubject) {
                if (!$this->selectedExamId && $foundSubject->exams()->exists()) {
                    $exam = $foundSubject->exams()->first();
                    $this->selectedExamId = $exam->id;
                    $this->updatedSelectedExamId($exam->id);
                }
                if (!in_array((string)$foundSubject->id, $this->selectedSubjects)) {
                    $this->selectedSubjects[] = (string)$foundSubject->id;
                }
                $this->currentStep = 3;
            }
        }

        $topicParam = $topic ?? request('topic');
        if ($topicParam) {
            $this->selectedTopicId = (int) $topicParam;
            $foundTopic = \App\Models\Topic::find($this->selectedTopicId);
            if ($foundTopic) {
                $topicSubj = $foundTopic->subject;
                if ($topicSubj) {
                    if (!$this->selectedExamId) {
                        $this->selectedExamId = $topicSubj->exam_id;
                        $this->updatedSelectedExamId($topicSubj->exam_id);
                    }
                    if (!in_array((string)$topicSubj->id, $this->selectedSubjects)) {
                        $this->selectedSubjects = [(string)$topicSubj->id];
                    }
                    $this->currentStep = 4;
                }
            }
        }

        $courseParam = $course ?? request('course');
        if ($courseParam) {
            if (!$this->selectedExamId) {
                $utme = Exam::where('slug', 'utme')->first();
                if ($utme) {
                    $this->selectedExamId = $utme->id;
                    $this->updatedSelectedExamId($utme->id);
                }
            }
            $this->selectCoursePreset($courseParam);
            $this->currentStep = 3;
        }
    }

    public function selectCoursePreset(string $courseSlug)
    {
        $this->selectedCourse = $courseSlug;
        if (!$courseSlug || !$this->selectedExamId) {
            return;
        }

        $subjectIds = \App\Services\JambBrochureService::mapCourseToSubjectIds($courseSlug, (int) $this->selectedExamId);
        if (!empty($subjectIds)) {
            $this->selectedSubjects = $subjectIds;
            $this->resetErrorBag('selectedSubjects');
        }
    }

    public function updatedSelectedExamId($examId)
    {
        $this->selectedSubjects = [];
        if ($examId) {
            $exam = Exam::find($examId);
            if ($exam && $exam->slug === 'utme') {
                $english = $exam->subjects()->where('slug', 'english-language')->first();
                if ($english) {
                    $this->selectedSubjects[] = (string) $english->id;
                }
            }
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
            'topic_id' => $this->selectedTopicId ? (int) $this->selectedTopicId : null,
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
        $exams = Exam::active()->get();
        $selectedExam = $this->selectedExamId ? Exam::find($this->selectedExamId) : null;
        $subjects = $selectedExam ? $selectedExam->subjects : collect();
        $years = config('cbtwise.exam_years', range(now()->year, 2000));

        // Available topics if single subject is selected in practice/study mode
        $availableTopics = collect();
        if (count($this->selectedSubjects) === 1) {
            $availableTopics = \App\Models\Topic::where('subject_id', $this->selectedSubjects[0])
                ->orderBy('sort_order')
                ->get();
        }

        return view('livewire.exam.setup', [
            'exams' => $exams,
            'selectedExam' => $selectedExam,
            'subjects' => $subjects,
            'years' => $years,
            'availableTopics' => $availableTopics,
        ])->layout('layouts.app');
    }
}
