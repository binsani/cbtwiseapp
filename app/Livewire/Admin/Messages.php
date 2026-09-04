<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;

class Messages extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ContactMessage::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        $messages = $query->latest()->paginate(15);
        $totalMessages = ContactMessage::count();

        return view('livewire.admin.messages', [
            'messages' => $messages,
            'totalMessages' => $totalMessages,
        ])->layout('layouts.app');
    }
}
