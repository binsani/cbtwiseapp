<?php

namespace App\Livewire\Tools;

use App\Models\Exam;
use Livewire\Component;

class PostUtmePlanner extends Component
{
    public string $institution = '';
    public string $course = '';

    public function startPractice()
    {
        $this->validate([
            'institution' => ['nullable', 'string', 'max:150'],
            'course' => ['nullable', 'string', 'max:150'],
        ]);

        return redirect()->route('exam.setup', [
            'exam' => 'post-utme',
        ]);
    }

    public function render()
    {
        return view('livewire.tools.post-utme-planner', [
            'postUtmeExam' => Exam::where('slug', 'post-utme')->first(),
        ])->layout('layouts.app');
    }
}
