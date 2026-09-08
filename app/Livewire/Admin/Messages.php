<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use App\Services\AdminLogger;
use Livewire\Component;
use Livewire\WithPagination;

class Messages extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, new, read, replied, spam
    
    public $selectedMessage = null;
    public $isViewModalOpen = false;
    
    public $replyText = '';
    public $internalNotes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function viewMessage($id)
    {
        $this->selectedMessage = ContactMessage::findOrFail($id);
        $this->selectedMessage->markAsRead();
        $this->replyText = $this->selectedMessage->reply ?? '';
        $this->internalNotes = $this->selectedMessage->internal_notes ?? '';
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->selectedMessage = null;
        $this->replyText = '';
        $this->internalNotes = '';
    }

    public function sendReply()
    {
        $this->validate([
            'replyText' => 'required|string|min:5',
        ]);

        if (!$this->selectedMessage) {
            return;
        }

        $this->selectedMessage->recordReply($this->replyText);
        $this->selectedMessage->update(['internal_notes' => $this->internalNotes]);

        AdminLogger::log('contact_message.replied', $this->selectedMessage, [
            'recipient' => $this->selectedMessage->email,
            'reply_length' => strlen($this->replyText),
        ]);

        session()->flash('message', "Reply recorded for {$this->selectedMessage->name}.");
        $this->closeViewModal();
    }

    public function markAsSpam($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->markAsSpam();

        AdminLogger::log('contact_message.marked_spam', $msg);
        session()->flash('message', 'Message marked as spam.');
    }

    public function deleteMessage($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->delete();

        if ($this->selectedMessage && $this->selectedMessage->id === $id) {
            $this->closeViewModal();
        }

        AdminLogger::log('contact_message.deleted', $msg);
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

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $messages = $query->latest()->paginate(15);
        $totalMessages = ContactMessage::count();
        $newMessagesCount = ContactMessage::where('status', 'new')->count();

        return view('livewire.admin.messages', [
            'messages' => $messages,
            'totalMessages' => $totalMessages,
            'newMessagesCount' => $newMessagesCount,
        ])->layout('layouts.app');
    }
}
