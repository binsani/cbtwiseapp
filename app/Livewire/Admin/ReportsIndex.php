<?php

namespace App\Livewire\Admin;

use App\Models\QuestionReport;
use App\Services\AdminLogger;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsIndex extends Component
{
    use WithPagination;

    public $status = 'open'; // open, dismissed, fixed
    public $search = '';

    public $viewingReport = null;
    public $isViewModalOpen = false;
    
    protected $queryString = [
        'status' => ['except' => 'open'],
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function openReportModal($id)
    {
        $this->viewingReport = QuestionReport::with(['question.exam', 'question.subject', 'reporter'])->findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeReportModal()
    {
        $this->isViewModalOpen = false;
        $this->viewingReport = null;
    }

    public function dismissReport($reportId)
    {
        $report = QuestionReport::findOrFail($reportId);
        
        $report->update([
            'status' => 'dismissed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        AdminLogger::log(
            'report.dismissed',
            $report,
            ['question_id' => $report->question_id]
        );

        $question = $report->question;
        if ($question && $question->reports_count > 0) {
            $question->decrement('reports_count');
        }

        if ($this->viewingReport && $this->viewingReport->id === $reportId) {
            $this->closeReportModal();
        }

        session()->flash('message', 'Report marked as dismissed.');
    }

    public function resolveReport($reportId)
    {
        $report = QuestionReport::findOrFail($reportId);
        
        $report->update([
            'status' => 'fixed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        AdminLogger::log(
            'report.resolved',
            $report,
            ['question_id' => $report->question_id]
        );

        if ($report->question) {
            $report->question->update(['is_flagged' => true]);
        }

        if ($this->viewingReport && $this->viewingReport->id === $reportId) {
            $this->closeReportModal();
        }

        session()->flash('message', 'Report marked as resolved. Question has been flagged for moderation.');
    }

    public function render()
    {
        $openCount = QuestionReport::where('status', 'open')->count();
        $resolvedCount = QuestionReport::where('status', 'fixed')->count();
        $dismissedCount = QuestionReport::where('status', 'dismissed')->count();

        $query = QuestionReport::query()
            ->where('status', $this->status)
            ->with(['question.exam', 'question.subject', 'reporter']);

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('reason', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%')
                  ->orWhereHas('question', function($sub) {
                      $sub->where('question_text', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $reports = $query->latest()->paginate(15);

        return view('livewire.admin.reports-index', [
            'reports' => $reports,
            'openCount' => $openCount,
            'resolvedCount' => $resolvedCount,
            'dismissedCount' => $dismissedCount,
        ])->layout('layouts.app');
    }
}
