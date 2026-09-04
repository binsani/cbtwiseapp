<?php

namespace App\Livewire\Schools;

use App\Models\School;
use App\Models\SchoolAssignment;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssignmentCreate extends Component
{
    public $school;
    public $title;
    public $instructions;
    public $exam_id;
    public $subject_id;
    public $question_count = 40;
    public $time_limit_mins = 60;
    public $start_at;
    public $end_at;
    public $allow_retake = false;
    public $show_answers_after = true;
    
    // Filters
    public $selectedYears = [];
    public $availableYears = [];

    // Collections
    public $exams = [];
    public $subjects = [];

    protected $rules = [
        'title' => 'required|string|max:150|min:3',
        'instructions' => 'nullable|string|max:1000',
        'exam_id' => 'required|exists:exams,id',
        'subject_id' => 'required|exists:subjects,id',
        'question_count' => 'required|integer|min:5|max:100',
        'time_limit_mins' => 'required|integer|min:5|max:180',
        'start_at' => 'nullable|date',
        'end_at' => 'nullable|date|after_or_equal:start_at',
        'allow_retake' => 'boolean',
        'show_answers_after' => 'boolean',
    ];

    public function mount(string $slug)
    {
        $this->school = School::where('slug', $slug)->firstOrFail();
        
        $this->exams = Exam::active()->get();
        $this->subjects = Subject::active()->get();

        // Default years
        $this->availableYears = range(date('Y'), 2000);
    }

    public function updatedExamId()
    {
        $this->loadYears();
    }

    public function updatedSubjectId()
    {
        $this->loadYears();
    }

    public function loadYears()
    {
        if ($this->exam_id && $this->subject_id) {
            $this->availableYears = Question::where('exam_id', $this->exam_id)
                ->where('subject_id', $this->subject_id)
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year')
                ->toArray();
        }
    }

    public function createAssignment()
    {
        $this->validate();

        SchoolAssignment::create([
            'school_id' => $this->school->id,
            'creator_id' => Auth::id(),
            'exam_id' => $this->exam_id,
            'subject_id' => $this->subject_id,
            'title' => $this->title,
            'instructions' => $this->instructions,
            'question_count' => $this->question_count,
            'time_limit_mins' => $this->time_limit_mins,
            'start_at' => $this->start_at ? now()->parse($this->start_at) : null,
            'end_at' => $this->end_at ? now()->parse($this->end_at) : null,
            'allow_retake' => $this->allow_retake,
            'show_answers_after' => $this->show_answers_after,
            'year_filter' => !empty($this->selectedYears) ? $this->selectedYears : null,
        ]);

        session()->flash('success', 'Assignment created successfully.');
        return redirect()->route('schools.dashboard', ['slug' => $this->school->slug]);
    }

    public function render()
    {
        return view('livewire.schools.assignment-create')->layout('layouts.app');
    }
}
