<?php

namespace App\Livewire\Exam;

use App\Models\ExamSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public function retakeSession($sessionId)
    {
        $user = Auth::user();
        
        // Find existing session
        $oldSession = ExamSession::where('user_id', $user->id)
            ->findOrFail($sessionId);

        // Check daily limit for free users
        if ($user->hasReachedDailyLimit()) {
            session()->flash('error', 'You have reached your free daily practice limit. Upgrade to Premium for unlimited practice.');
            return;
        }

        // Create identical session config
        $newSession = ExamSession::create([
            'user_id' => $user->id,
            'exam_id' => $oldSession->exam_id,
            'mode' => $oldSession->mode,
            'subjects' => $oldSession->subjects,
            'year' => $oldSession->year,
            'total_questions' => $oldSession->total_questions,
            'duration_seconds' => $oldSession->duration_seconds,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('exam.run', ['session' => $newSession->id]);
    }

    public function render()
    {
        $sessions = ExamSession::where('user_id', Auth::id())
            ->where('status', 'submitted')
            ->with('exam')
            ->latest('submitted_at')
            ->paginate(10);

        return view('livewire.exam.history', [
            'sessions' => $sessions,
        ])->layout('layouts.app');
    }
}
