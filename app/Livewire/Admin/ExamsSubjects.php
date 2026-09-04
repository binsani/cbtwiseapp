<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ExamsSubjects extends Component
{
    use WithPagination;

    // View state
    public $activeTab = 'exams'; // exams or subjects

    // Modals
    public $isExamModalOpen = false;
    public $isSubjectModalOpen = false;
    public $isEditMode = false;

    // Exam Form fields
    public $editingExamId = null;
    public $exam_name;
    public $exam_slug;
    public $exam_description;
    public $exam_duration_minutes_default = 180;
    public $exam_questions_per_subject_default = 40;
    public $exam_is_active = true;

    // Subject Form fields
    public $editingSubjectId = null;
    public $subject_exam_id;
    public $subject_name;
    public $subject_slug;
    public $subject_icon = '📚';
    public $subject_sort_order = 0;
    public $subject_is_active = true;

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // --- Exam CRUD ---

    public function openAddExamModal()
    {
        $this->resetExamForm();
        $this->isEditMode = false;
        $this->isExamModalOpen = true;
    }

    public function openEditExamModal($id)
    {
        $this->resetExamForm();
        $exam = Exam::findOrFail($id);
        
        $this->editingExamId = $exam->id;
        $this->exam_name = $exam->name;
        $this->exam_slug = $exam->slug;
        $this->exam_description = $exam->description;
        $this->exam_duration_minutes_default = $exam->duration_minutes_default;
        $this->exam_questions_per_subject_default = $exam->questions_per_subject_default;
        $this->exam_is_active = $exam->is_active;

        $this->isEditMode = true;
        $this->isExamModalOpen = true;
    }

    public function resetExamForm()
    {
        $this->editingExamId = null;
        $this->exam_name = '';
        $this->exam_slug = '';
        $this->exam_description = '';
        $this->exam_duration_minutes_default = 180;
        $this->exam_questions_per_subject_default = 40;
        $this->exam_is_active = true;
    }

    public function saveExam()
    {
        $this->exam_slug = Str::slug($this->exam_slug ?: $this->exam_name);

        $rules = [
            'exam_name' => 'required|string|max:100',
            'exam_slug' => 'required|string|max:20|unique:exams,slug,' . ($this->editingExamId ?? 'NULL'),
            'exam_description' => 'nullable|string',
            'exam_duration_minutes_default' => 'required|integer|min:1',
            'exam_questions_per_subject_default' => 'required|integer|min:1',
            'exam_is_active' => 'required|boolean',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->exam_name,
            'slug' => $this->exam_slug,
            'description' => $this->exam_description,
            'duration_minutes_default' => $this->exam_duration_minutes_default,
            'questions_per_subject_default' => $this->exam_questions_per_subject_default,
            'is_active' => $this->exam_is_active,
        ];

        if ($this->isEditMode) {
            Exam::findOrFail($this->editingExamId)->update($data);
            session()->flash('message', 'Exam updated successfully.');
        } else {
            Exam::create($data);
            session()->flash('message', 'Exam created successfully.');
        }

        $this->isExamModalOpen = false;
        $this->resetExamForm();
    }

    public function deleteExam($id)
    {
        Exam::findOrFail($id)->delete();
        session()->flash('message', 'Exam deleted successfully.');
    }

    // --- Subject CRUD ---

    public function openAddSubjectModal()
    {
        $this->resetSubjectForm();
        $this->isEditMode = false;
        $this->isSubjectModalOpen = true;
    }

    public function openEditSubjectModal($id)
    {
        $this->resetSubjectForm();
        $subject = Subject::findOrFail($id);

        $this->editingSubjectId = $subject->id;
        $this->subject_exam_id = $subject->exam_id;
        $this->subject_name = $subject->name;
        $this->subject_slug = $subject->slug;
        $this->subject_icon = $subject->icon ?? '📚';
        $this->subject_sort_order = $subject->sort_order;
        $this->subject_is_active = $subject->is_active;

        $this->isEditMode = true;
        $this->isSubjectModalOpen = true;
    }

    public function resetSubjectForm()
    {
        $this->editingSubjectId = null;
        $this->subject_exam_id = null;
        $this->subject_name = '';
        $this->subject_slug = '';
        $this->subject_icon = '📚';
        $this->subject_sort_order = 0;
        $this->subject_is_active = true;
    }

    public function saveSubject()
    {
        $this->subject_slug = Str::slug($this->subject_slug ?: $this->subject_name);

        $rules = [
            'subject_exam_id' => 'required|exists:exams,id',
            'subject_name' => 'required|string|max:100',
            'subject_slug' => 'required|string|max:60|unique:subjects,slug,' . ($this->editingSubjectId ?? 'NULL') . ',id,exam_id,' . $this->subject_exam_id,
            'subject_icon' => 'nullable|string|max:10',
            'subject_sort_order' => 'required|integer|min:0',
            'subject_is_active' => 'required|boolean',
        ];

        $this->validate($rules);

        $data = [
            'exam_id' => $this->subject_exam_id,
            'name' => $this->subject_name,
            'slug' => $this->subject_slug,
            'icon' => $this->subject_icon,
            'sort_order' => $this->subject_sort_order,
            'is_active' => $this->subject_is_active,
        ];

        if ($this->isEditMode) {
            Subject::findOrFail($this->editingSubjectId)->update($data);
            session()->flash('message', 'Subject updated successfully.');
        } else {
            Subject::create($data);
            session()->flash('message', 'Subject created successfully.');
        }

        $this->isSubjectModalOpen = false;
        $this->resetSubjectForm();
    }

    public function deleteSubject($id)
    {
        Subject::findOrFail($id)->delete();
        session()->flash('message', 'Subject deleted successfully.');
    }

    public function render()
    {
        $examsCount = Exam::count();
        $subjectsCount = Subject::count();

        $exams = Exam::withCount('subjects')->latest()->paginate(15);
        $subjects = Subject::with('exam')->orderBy('exam_id')->orderBy('sort_order')->paginate(20);

        return view('livewire.admin.exams-subjects', [
            'exams' => $exams,
            'subjects' => $subjects,
            'examsCount' => $examsCount,
            'subjectsCount' => $subjectsCount,
            'allExams' => Exam::all(),
        ])->layout('layouts.app');
    }
}
