<?php
namespace App\Livewire\Tools;
use App\Models\Topic;
use Livewire\Component;
class TopicLessonPage extends Component { public Topic $topic; public function mount(Topic $topic): void { $this->topic = $topic->load(['subject.exam','lesson']); } public function render() { $lesson = $this->topic->lesson?->status === 'published' ? $this->topic->lesson : null; return view('livewire.tools.topic-lesson-page', compact('lesson'))->layout('layouts.app'); } }
