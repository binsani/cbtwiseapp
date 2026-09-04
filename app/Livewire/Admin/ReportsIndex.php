<?php

namespace App\Livewire\Admin;

use App\Models\QuestionReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsIndex extends Component
{
    use WithPagination;

    public $status = 'open'; // open, dismissed, fixed
    public $search = '';
    
    protected $queryString = [
        'status' => ['except' => 'open'],
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function dismissReport($reportId)
    {
        $report = QuestionReport::findOrFail($reportId);
        
        $report->update([
            'status' => 'dismissed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Record audit log
        \App\Models\AdminActivityLog::record(
            Auth::id(),
            'report.dismissed',
            QuestionReport::class,
            $reportId,
            ['question_id' => $report->question_id]
        );

        // Decrement reports count on question and auto unflag if needed
        $question = $report->question;
        if ($question && $question->reports_count > 0) {
            $question->decrement('reports_count');
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

        // Record audit log
        \App\Models\AdminActivityLog::record(
            Auth::id(),
            'report.resolved',
            QuestionReport::class,
            $reportId,
            ['question_id' => $report->question_id]
        );

        // Flag the question for edit
        if ($report->question) {
            $report->question->update(['is_flagged' => true]);
        }

        session()->flash('message', 'Report marked as fixed. Question has been flagged for moderation.');
    }

    public function render()
    {
        // Totals counts for pills
        $pendingCount = QuestionReport::where('status', 'open')->count();

        $query = QuestionReport::query()
            ->where('status', $this->status)
            ->with(['question', 'reporter']);

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
            'pendingCount' => $pendingCount,
        ])->layout('layouts.app');
    }
}
