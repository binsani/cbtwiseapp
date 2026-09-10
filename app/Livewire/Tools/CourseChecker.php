<?php

namespace App\Livewire\Tools;

use App\Services\JambBrochureService;
use Livewire\Component;

class CourseChecker extends Component
{
    public string $search = '';
    public string $selectedFaculty = '';
    public ?string $selectedCourseSlug = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedFaculty' => ['except' => ''],
        'selectedCourseSlug' => ['except' => null, 'as' => 'course'],
    ];

    public function selectFaculty(string $key)
    {
        $this->selectedFaculty = ($this->selectedFaculty === $key) ? '' : $key;
    }

    public function selectCourse(string $slug)
    {
        $this->selectedCourseSlug = $slug;
    }

    public function clearSelectedCourse()
    {
        $this->selectedCourseSlug = null;
    }

    public function render()
    {
        $faculties = JambBrochureService::getFaculties();
        $courses = JambBrochureService::search($this->search, $this->selectedFaculty);
        
        $activeCourse = $this->selectedCourseSlug 
            ? JambBrochureService::findBySlug($this->selectedCourseSlug) 
            : null;

        return view('livewire.tools.course-checker', [
            'faculties' => $faculties,
            'courses' => $courses,
            'activeCourse' => $activeCourse,
        ])->layout('layouts.app');
    }
}
