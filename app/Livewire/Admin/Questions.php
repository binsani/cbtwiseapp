<?php

namespace App\Livewire\Admin;

use App\Models\Question;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Topic;
use Livewire\Component;
use Livewire\WithPagination;

class Questions extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $examFilter = 'all'; // all, UTME, WAEC, NECO (or exam IDs)
    public $subjectFilter = 'all';
    
    // Form management state
    public $isFormOpen = false;
    public $isEditMode = false;
    public $editingQuestionId = null;

    // Question Form attributes
    public $exam_id;
    public $subject_id;
    public $topic_id;
    public $year;
    public $difficulty = 'easy';
    public $question_text;
    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;
    public $option_e;
    public $correct_option = 'a';
    public $explanation;

    // Selections lists
    public $exams = [];
    public $subjects = [];
    public $topics = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'examFilter' => ['except' => 'all'],
        'subjectFilter' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->exams = Exam::all();
        $this->subjects = Subject::all();
        $this->topics = Topic::all();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedExamFilter()
    {
        $this->resetPage();
    }

    public function updatedSubjectFilter()
    {
        $this->resetPage();
    }

    public function toggleFlag($id)
    {
        $question = Question::findOrFail($id);
        $question->is_flagged = !$question->is_flagged;
        $question->save();

        session()->flash('message', $question->is_flagged ? 'Question flagged for review.' : 'Question flag removed.');
    }

    public function updatedExamId($value)
    {
        // Filter subjects based on selected exam
        if ($value) {
            $this->subjects = Subject::where('exam_id', $value)->get();
            $this->subject_id = null;
            $this->topic_id = null;
            $this->topics = [];
        } else {
            $this->subjects = Subject::all();
            $this->topics = Topic::all();
        }
    }

    public function updatedSubjectId($value)
    {
        if ($value) {
            $this->topics = Topic::where('subject_id', $value)->get();
            $this->topic_id = null;
        } else {
            $this->topics = Topic::all();
        }
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->isFormOpen = true;
    }

    public function openEditForm($id)
    {
        $this->resetForm();
        $question = Question::findOrFail($id);
        
        $this->editingQuestionId = $id;
        $this->exam_id = $question->exam_id;
        $this->subject_id = $question->subject_id;
        $this->topic_id = $question->topic_id;
        $this->year = $question->year;
        $this->difficulty = $question->difficulty ?? 'easy';
        $this->question_text = $question->question_text;
        $this->option_a = $question->option_a;
        $this->option_b = $question->option_b;
        $this->option_c = $question->option_c;
        $this->option_d = $question->option_d;
        $this->option_e = $question->option_e;
        $this->correct_option = $question->correct_option;
        $this->explanation = $question->explanation;

        // Populate cascading dropdowns
        $this->subjects = Subject::where('exam_id', $this->exam_id)->get();
        if ($this->subject_id) {
            $this->topics = Topic::where('subject_id', $this->subject_id)->get();
        }

        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    public function resetForm()
    {
        $this->editingQuestionId = null;
        $this->exam_id = null;
        $this->subject_id = null;
        $this->topic_id = null;
        $this->year = null;
        $this->difficulty = 'easy';
        $this->question_text = '';
        $this->option_a = '';
        $this->option_b = '';
        $this->option_c = '';
        $this->option_d = '';
        $this->option_e = '';
        $this->correct_option = 'a';
        $this->explanation = '';
    }

    public function saveQuestion()
    {
        $rules = [
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'difficulty' => 'required|in:easy,medium,hard',
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'correct_option' => 'required|in:a,b,c,d,e',
            'explanation' => 'nullable|string',
        ];

        $this->validate($rules);

        $data = [
            'exam_id' => $this->exam_id,
            'subject_id' => $this->subject_id,
            'topic_id' => $this->topic_id,
            'year' => $this->year,
            'difficulty' => $this->difficulty,
            'question_text' => $this->question_text,
            'option_a' => $this->option_a,
            'option_b' => $this->option_b,
            'option_c' => $this->option_c,
            'option_d' => $this->option_d,
            'option_e' => $this->option_e ?: null,
            'correct_option' => $this->correct_option,
            'explanation' => $this->explanation,
            'created_by' => auth()->id(),
        ];

        if ($this->isEditMode) {
            $question = Question::findOrFail($this->editingQuestionId);
            $question->update($data);
            session()->flash('message', 'Question updated successfully.');
        } else {
            // Dedupe check
            $hash = Question::dedupeHash($this->question_text);
            $data['dedupe_hash'] = $hash;
            $data['source'] = 'manual';

            if (Question::where('dedupe_hash', $hash)->exists()) {
                $this->addError('question_text', 'A duplicate question with similar content already exists.');
                return;
            }

            Question::create($data);
            session()->flash('message', 'Question added successfully.');
        }

        $this->isFormOpen = false;
        $this->resetForm();
    }

    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        session()->flash('message', 'Question deleted successfully.');
    }

    public function render()
    {
        $query = Question::query()->with(['exam', 'subject']);

        if (!empty($this->search)) {
            $query->where('question_text', 'like', '%' . $this->search . '%');
        }

        if ($this->examFilter !== 'all') {
            $query->whereHas('exam', function ($q) {
                $q->where('slug', strtolower($this->examFilter))
                  ->orWhere('name', 'like', '%' . $this->examFilter . '%');
            });
        }

        if ($this->subjectFilter !== 'all') {
            $query->where('subject_id', $this->subjectFilter);
        }

        $questions = $query->latest()->paginate(15);
        $totalQuestionsInBank = Question::count();
        $allFilterSubjects = Subject::orderBy('name')->get();

        return view('livewire.admin.questions', [
            'questions' => $questions,
            'totalQuestionsInBank' => $totalQuestionsInBank,
            'allFilterSubjects' => $allFilterSubjects,
        ])->layout('layouts.app');
    }
}
