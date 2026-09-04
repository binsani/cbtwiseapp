<?php

namespace App\Livewire\Exam;

use App\Models\Question;
use App\Models\QuestionReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportQuestion extends Component
{
    public $questionId;
    public $reason = 'wrong_answer'; // default
    public $notes = '';
    
    public $isOpen = false;

    protected $rules = [
        'questionId' => 'required|exists:questions,id',
        'reason' => 'required|in:wrong_answer,typo,offensive,duplicate,other',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $listeners = ['openReportModal' => 'open'];

    public function open($questionId)
    {
        $this->questionId = $questionId;
        $this->isOpen = true;
        $this->resetValidation();
        $this->reset(['notes', 'reason']);
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function submitReport()
    {
        $this->validate();

        $question = Question::findOrFail($this->questionId);

        // Create the report
        QuestionReport::create([
            'question_id' => $question->id,
            'user_id' => Auth::id(),
            'reason' => $this->reason,
            'notes' => $this->notes,
            'status' => 'open',
        ]);

        // Increment question reports count and check for auto-flagging
        $question->increment('reports_count');
        $question->autoFlagIfNeeded();

        $this->isOpen = false;
        
        $this->dispatch('reportSubmitted', 'Thank you for reporting. Our moderators will review this question.');
    }

    public function render()
    {
        return view('livewire.exam.report-question');
    }
}
