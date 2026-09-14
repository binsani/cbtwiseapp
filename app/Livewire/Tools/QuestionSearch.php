<?php

namespace App\Livewire\Tools;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $selectedExamId = null;
    public ?int $selectedSubjectId = null;
    public string $selectedYear = '';

    public function mount(): void
    {
        $this->selectedExamId = Exam::where('slug', 'utme')->value('id') ?? Exam::value('id');
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedSelectedExamId(): void
    {
        $this->selectedSubjectId = null;
        $this->resetPage();
    }
    public function updatedSelectedSubjectId(): void { $this->resetPage(); }
    public function updatedSelectedYear(): void { $this->resetPage(); }

    public function render()
    {
        $exams = Exam::active()->orderBy('name')->get(['id', 'name', 'slug']);
        $subjects = $this->selectedExamId
            ? Subject::where('exam_id', $this->selectedExamId)->orderBy('sort_order')->get(['id', 'name'])
            : collect();

        $query = Question::query()
            ->with(['exam:id,name,slug', 'subject:id,name', 'topic:id,name'])
            ->where('is_flagged', false);

        if ($this->selectedExamId) {
            $query->where('exam_id', $this->selectedExamId);
        }
        if ($this->selectedSubjectId) {
            $query->where('subject_id', $this->selectedSubjectId);
        }
        if ($this->selectedYear !== '') {
            $query->where('year', (int) $this->selectedYear);
        }
        if (trim($this->search) !== '') {
            $term = '%' . addcslashes(trim($this->search), '%_\\') . '%';
            $query->where(function ($searchQuery) use ($term) {
                $searchQuery->where('question_text', 'like', $term)
                    ->orWhere('passage', 'like', $term)
                    ->orWhere('option_a', 'like', $term)
                    ->orWhere('option_b', 'like', $term)
                    ->orWhere('option_c', 'like', $term)
                    ->orWhere('option_d', 'like', $term)
                    ->orWhere('option_e', 'like', $term);
            });
        }

        return view('livewire.tools.question-search', [
            'exams' => $exams,
            'subjects' => $subjects,
            'years' => Question::whereNotNull('year')->distinct()->orderByDesc('year')->pluck('year'),
            'questions' => $query->latest('id')->paginate(12),
        ])->layout('layouts.app');
    }
}
