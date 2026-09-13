<?php

namespace App\Livewire\Tools;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Topic;
use Livewire\Component;

class TopicExplorer extends Component
{
    public $selectedExamId = null;
    public $selectedSubjectId = null;
    public string $search = '';

    public function mount()
    {
        $examParam = request('exam');
        if ($examParam) {
            $exam = is_numeric($examParam) ? Exam::find($examParam) : Exam::where('slug', $examParam)->first();
            if ($exam) {
                $this->selectedExamId = $exam->id;
            }
        }

        if (!$this->selectedExamId) {
            $firstExam = Exam::where('slug', 'utme')->first() ?? Exam::first();
            if ($firstExam) {
                $this->selectedExamId = $firstExam->id;
            }
        }

        $subjectParam = request('subject');
        if ($subjectParam) {
            $subject = is_numeric($subjectParam) ? Subject::find($subjectParam) : Subject::where('slug', $subjectParam)->first();
            if ($subject) {
                $this->selectedSubjectId = $subject->id;
                $this->selectedExamId = $subject->exam_id;
            }
        }

        if (!$this->selectedSubjectId && $this->selectedExamId) {
            $firstSubj = Subject::where('exam_id', $this->selectedExamId)->orderBy('sort_order')->first();
            if ($firstSubj) {
                $this->selectedSubjectId = $firstSubj->id;
            }
        }
    }

    public function selectExam(int $examId)
    {
        $this->selectedExamId = $examId;
        $firstSubj = Subject::where('exam_id', $examId)->orderBy('sort_order')->first();
        $this->selectedSubjectId = $firstSubj?->id;
    }

    public function selectSubject(int $subjectId)
    {
        $this->selectedSubjectId = $subjectId;
    }

    public function render()
    {
        $exams = Exam::active()->get();
        $subjects = $this->selectedExamId 
            ? Subject::where('exam_id', $this->selectedExamId)->orderBy('sort_order')->get()
            : collect();

        $topicsQuery = Topic::query();
        if ($this->selectedSubjectId) {
            $topicsQuery->where('subject_id', $this->selectedSubjectId);
        }

        if (trim($this->search)) {
            $topicsQuery->where('name', 'like', '%' . trim($this->search) . '%');
        }

        $topics = $topicsQuery->withCount('questions')->orderBy('sort_order')->get();
        $activeSubject = $this->selectedSubjectId ? Subject::find($this->selectedSubjectId) : null;
        $activeExam = $this->selectedExamId ? Exam::find($this->selectedExamId) : null;
        $syllabusSource = config('syllabus_sources.' . $activeExam?->slug);

        return view('livewire.tools.topic-explorer', [
            'exams' => $exams,
            'subjects' => $subjects,
            'topics' => $topics,
            'activeSubject' => $activeSubject,
            'activeExam' => $activeExam,
            'syllabusSource' => $syllabusSource,
        ])->layout('layouts.app');
    }
}
