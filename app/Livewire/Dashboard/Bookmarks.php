<?php

namespace App\Livewire\Dashboard;

use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Bookmarks extends Component
{
    use WithPagination;

    public function removeBookmark($questionId)
    {
        Bookmark::where('user_id', Auth::id())
            ->where('question_id', $questionId)
            ->delete();

        session()->flash('message', 'Question removed from bookmarks.');
    }

    public function render()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->with(['question.subject', 'question.exam'])
            ->latest()
            ->paginate(8);

        return view('livewire.dashboard.bookmarks', compact('bookmarks'))
            ->layout('layouts.app');
    }
}
