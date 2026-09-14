<?php
namespace App\Livewire\Admin;
use App\Models\Topic;
use App\Models\TopicLesson;
use Livewire\Component;

class TopicLessons extends Component
{
    public ?int $topicId = null;
    public string $title = '';
    public string $lessonNotes = '';
    public string $exampleQuestion = '';
    public string $exampleSolution = '';
    public string $status = 'draft';

    public function updatedTopicId(): void
    {
        $lesson = $this->topicId ? TopicLesson::where('topic_id', $this->topicId)->first() : null;
        $this->title = $lesson?->title ?? '';
        $this->lessonNotes = $lesson?->lesson_notes ?? '';
        $this->exampleQuestion = $lesson?->worked_example_question ?? '';
        $this->exampleSolution = $lesson?->worked_example_solution ?? '';
        $this->status = $lesson?->status ?? 'draft';
    }

    public function save(): void
    {
        $data = $this->validate([
            'topicId' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:180'],
            'lessonNotes' => ['nullable', 'string', 'max:50000'],
            'exampleQuestion' => ['nullable', 'string', 'max:5000'],
            'exampleSolution' => ['nullable', 'string', 'max:20000'],
            'status' => ['required', 'in:draft,published'],
        ]);
        TopicLesson::updateOrCreate(['topic_id' => $data['topicId']], [
            'title' => $data['title'], 'lesson_notes' => $data['lessonNotes'] ?: null,
            'worked_example_question' => $data['exampleQuestion'] ?: null,
            'worked_example_solution' => $data['exampleSolution'] ?: null, 'status' => $data['status'],
        ]);
        session()->flash('message', 'Topic lesson saved. Only published lessons are visible to students.');
    }

    public function render()
    {
        return view('livewire.admin.topic-lessons', ['topics' => Topic::with('subject.exam')->orderBy('subject_id')->orderBy('sort_order')->get()])->layout('layouts.admin');
    }
}
