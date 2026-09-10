<?php

namespace App\Livewire\Dashboard;

use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Bookmarks extends Component
{
    use WithPagination;

    public $subjectFilter = '';
    public $search = '';

    public function updatedSubjectFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function removeBookmark($questionId)
    {
        Bookmark::where('user_id', Auth::id())
            ->where('question_id', $questionId)
            ->delete();

        session()->flash('message', 'Question removed from bookmarks.');
    }

    public function render()
    {
        $query = Bookmark::where('user_id', Auth::id())
            ->with(['question.subject', 'question.exam']);

        if ($this->subjectFilter) {
            $query->whereHas('question', function ($q) {
                $q->where('subject_id', $this->subjectFilter);
            });
        }

        if ($this->search) {
            $query->whereHas('question', function ($q) {
                $q->where('question_text', 'like', '%' . $this->search . '%');
            });
        }

        $bookmarks = $query->latest()->paginate(8);

        $availableSubjects = \App\Models\Subject::whereIn('id', function ($q) {
            $q->select('questions.subject_id')
                ->from('bookmarks')
                ->join('questions', 'bookmarks.question_id', '=', 'questions.id')
                ->where('bookmarks.user_id', Auth::id());
        })->get();

        return view('livewire.dashboard.bookmarks', compact('bookmarks', 'availableSubjects'))
            ->layout('layouts.app');
    }
}
