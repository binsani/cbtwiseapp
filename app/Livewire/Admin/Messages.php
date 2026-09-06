<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;

class Messages extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMessage = null;
    public $isViewModalOpen = false;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function viewMessage($id)
    {
        $this->selectedMessage = ContactMessage::findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->selectedMessage = null;
    }

    public function deleteMessage($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->delete();

        if ($this->selectedMessage && $this->selectedMessage->id === $id) {
            $this->closeViewModal();
        }

        session()->flash('message', 'Message deleted successfully.');
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
