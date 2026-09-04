<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;

class ExamLandingController extends Controller
{
    public function index()
    {
        $exams = Exam::active()->withCount('questions')->get();
        return view('pages.exams', compact('exams'));
    }

    public function show(string $slug)
    {
        $exam = Exam::active()->where('slug', $slug)->firstOrFail();
        $subjects = Subject::active()->where('exam_id', $exam->id)->withCount('questions')->get();
        $yearRange = Question::where('exam_id', $exam->id)->whereNotNull('year')
            ->selectRaw('MIN(year) as min_year, MAX(year) as max_year')->first();
        $totalQuestions = Question::where('exam_id', $exam->id)->count();

        return view('pages.exam-detail', compact('exam', 'subjects', 'yearRange', 'totalQuestions'));
    }
}
