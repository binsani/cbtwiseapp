<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\SeoPage;
use App\Models\Subject;
use Illuminate\Http\Response;

class SeoPageController extends Controller
{
    public function show(string $examSlug, string $subjectSlug, string $year): Response|\Illuminate\View\View
    {
        $slug = SeoPage::buildSlug($examSlug, $subjectSlug, (int) $year);

        $page = SeoPage::with(['exam', 'subject'])
            ->published()
            ->where('slug', $slug)
            ->first();

        // If not in DB yet, try to serve dynamically if data exists
        if (! $page) {
            $exam    = Exam::where('slug', $examSlug)->first();
            $subject = Subject::where('slug', $subjectSlug)->first();

            if (! $exam || ! $subject) {
                abort(404);
            }

            // Build a lightweight on-the-fly page
            $page = new SeoPage([
                'slug'             => $slug,
                'exam_id'          => $exam->id,
                'subject_id'       => $subject->id,
                'year'             => (int) $year,
                'title'            => "{$exam->name} {$subject->name} {$year} Past Questions & Answers",
                'h1'               => "Practice {$exam->name} {$subject->name} {$year} Questions",
                'meta_description' => "Practise {$exam->name} {$subject->name} {$year} past questions with answers and explanations on CBTWise.",
                'body_md'          => null,
                'schema_json'      => null,
            ]);
            $page->exam    = $exam;
            $page->subject = $subject;
        } else {
            $page->incrementView();
        }

        // Load sample questions (up to 10)
        $questions = Question::query()
            ->with(['subject', 'topic'])
            ->where('exam_id', $page->exam_id)
            ->where('subject_id', $page->subject_id)
            ->where('year', $page->year)
            ->where('is_flagged', false)
            ->limit(10)
            ->inRandomOrder()
            ->get();

        // Related subjects (same exam, different subjects with questions in same year)
        $relatedSubjects = Subject::query()
            ->whereIn('id', Question::where('exam_id', $page->exam_id)
                ->where('year', $page->year)
                ->where('subject_id', '!=', $page->subject_id)
                ->distinct()
                ->pluck('subject_id'))
            ->limit(6)
            ->get();

        return view('seo.show', compact('page', 'questions', 'relatedSubjects'));
    }
}
