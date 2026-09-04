<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Question;
use App\Models\Topic;

class SubjectLandingController extends Controller
{
    public function index()
    {
        $subjects = Subject::active()->withCount('questions')->with('exam')->get()->groupBy(fn($s) => $s->exam?->name ?? 'General');
        return view('pages.subjects', compact('subjects'));
    }

    public function show(string $slug)
    {
        $subject = Subject::active()->where('slug', $slug)->with('exam')->firstOrFail();
        $topics = Topic::where('subject_id', $subject->id)->withCount('questions')->get();
        $sampleQuestions = Question::where('subject_id', $subject->id)->notFlagged()->take(3)->get();
        $yearRange = Question::where('subject_id', $subject->id)->whereNotNull('year')
            ->selectRaw('MIN(year) as min_year, MAX(year) as max_year')->first();
        $totalQuestions = Question::where('subject_id', $subject->id)->count();

        return view('pages.subject-detail', compact('subject', 'topics', 'sampleQuestions', 'yearRange', 'totalQuestions'));
    }
}
