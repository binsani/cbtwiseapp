<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use App\Http\Clients\AlocApiClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuestionFetcher
{
    protected AlocApiClient $alocClient;

    public function __construct(AlocApiClient $alocClient)
    {
        $this->alocClient = $alocClient;
    }

    /**
     * Fetch questions for a given exam, subject, count, and optional year.
     */
    public function fetch(Exam $exam, Subject $subject, int $count, ?int $year = null): Collection
    {
        // 1. Fetch from local DB using weighted random
        $query = Question::query()
            ->forExam($exam->id)
            ->forSubject($subject->id)
            ->notFlagged();

        if ($year) {
            $query->forYear($year);
        }

        $localQuestions = $query->weightedRandom()->limit($count)->get();

        // If we have enough questions, return them
        if ($localQuestions->count() >= $count) {
            return $localQuestions;
        }

        // 2. We have a shortage, let's fetch from the ALOC API
        $needed = $count - $localQuestions->count();
        Log::info("Shortage of {$needed} questions for Exam: {$exam->name}, Subject: {$subject->name}. Fetching from ALOC API...");

        // Subject name should be lowercase for ALOC API
        $alocSubjectName = strtolower($subject->name);
        // Map common subject names if they differ
        $subjectMapping = [
            'english language' => 'english',
            'christian religious studies' => 'crk',
            'islamic religious studies' => 'irk',
            'further mathematics' => 'furthermaths',
        ];
        if (isset($subjectMapping[$alocSubjectName])) {
            $alocSubjectName = $subjectMapping[$alocSubjectName];
        }

        $alocQuestionsData = $this->alocClient->fetchQuestions($alocSubjectName, max($needed, 20));

        $newQuestions = collect();

        foreach ($alocQuestionsData as $item) {
            $questionText = $item['question'] ?? $item['question_text'] ?? '';
            if (empty($questionText)) {
                continue;
            }

            // Normalise correct option (e.g. 'A' -> 'a')
            $correctOption = strtolower($item['answer'] ?? $item['correct_option'] ?? 'a');
            if (!in_array($correctOption, ['a', 'b', 'c', 'd', 'e'])) {
                $correctOption = 'a';
            }

            // Extract options
            $optionA = $item['option']['a'] ?? $item['option_a'] ?? '';
            $optionB = $item['option']['b'] ?? $item['option_b'] ?? '';
            $optionC = $item['option']['c'] ?? $item['option_c'] ?? '';
            $optionD = $item['option']['d'] ?? $item['option_d'] ?? '';
            $optionE = $item['option']['e'] ?? $item['option_e'] ?? null;

            if (empty($optionA) || empty($optionB)) {
                continue;
            }

            $hash = Question::dedupeHash($questionText);

            // Create or retrieve the question
            $question = Question::firstOrCreate(
                ['dedupe_hash' => $hash],
                [
                    'exam_id' => $exam->id,
                    'subject_id' => $subject->id,
                    'topic_id' => null,
                    'created_by' => Auth::id(),
                    'year' => $item['year'] ?? $year ?? now()->year,
                    'question_text' => $questionText,
                    'question_image' => $item['image'] ?? null,
                    'option_a' => $optionA,
                    'option_b' => $optionB,
                    'option_c' => $optionC,
                    'option_d' => $optionD,
                    'option_e' => $optionE,
                    'correct_option' => $correctOption,
                    'explanation' => $item['solution'] ?? $item['explanation'] ?? null,
                    'source' => 'aloc',
                ]
            );

            // Only add to result if not already flagged
            if (!$question->is_flagged) {
                $newQuestions->push($question);
            }
        }

        // Combine local and newly fetched questions
        $allQuestions = $localQuestions->concat($newQuestions);

        // If we still don't have enough, return whatever we managed to fetch, capped at the requested count
        return $allQuestions->unique('id')->take($count);
    }
}
