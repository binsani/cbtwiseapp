<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Http\Clients\AlocApiClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BulkSeeder extends Component
{
    public $batches = 3; // default: 3 batches (~120 questions/subject)

    public function startBulkFetch()
    {
        $alocClient = new AlocApiClient();
        $subjects = Subject::with('exam')->where('is_active', true)->get();
        $totalFetched = 0;
        $totalCreated = 0;

        // Map common subject names if they differ
        $subjectMapping = [
            'english language' => 'english',
            'christian religious studies' => 'crk',
            'islamic religious studies' => 'irk',
            'further mathematics' => 'furthermaths',
        ];

        // Fetch multiple batches to get different years/sets from ALOC API
        // Usually, 1 batch fetching 40 questions retrieves a single random collection.
        $questionsPerBatch = 40;

        foreach ($subjects as $subject) {
            $alocSubjectName = strtolower($subject->name);
            if (isset($subjectMapping[$alocSubjectName])) {
                $alocSubjectName = $subjectMapping[$alocSubjectName];
            }

            for ($b = 0; $b < $this->batches; $b++) {
                $alocQuestionsData = $alocClient->fetchQuestions($alocSubjectName, $questionsPerBatch);
                
                if (empty($alocQuestionsData)) {
                    continue;
                }

                foreach ($alocQuestionsData as $item) {
                    $questionText = $item['question'] ?? $item['question_text'] ?? '';
                    if (empty($questionText)) {
                        continue;
                    }

                    $totalFetched++;

                    // Normalise correct option
                    $correctOption = strtolower($item['answer'] ?? $item['correct_option'] ?? 'a');
                    if (!in_array($correctOption, ['a', 'b', 'c', 'd', 'e'])) {
                        $correctOption = 'a';
                    }

                    $optionA = $item['option']['a'] ?? $item['option_a'] ?? '';
                    $optionB = $item['option']['b'] ?? $item['option_b'] ?? '';
                    $optionC = $item['option']['c'] ?? $item['option_c'] ?? '';
                    $optionD = $item['option']['d'] ?? $item['option_d'] ?? '';
                    $optionE = $item['option']['e'] ?? $item['option_e'] ?? null;

                    if (empty($optionA) || empty($optionB)) {
                        continue;
                    }

                    $hash = Question::dedupeHash($questionText);

                    // Check if exists
                    $exists = Question::where('dedupe_hash', $hash)->exists();
                    if (!$exists) {
                        Question::create([
                            'dedupe_hash' => $hash,
                            'exam_id' => $subject->exam_id,
                            'subject_id' => $subject->id,
                            'topic_id' => null,
                            'created_by' => Auth::id(),
                            'year' => $item['year'] ?? now()->year,
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
                        ]);
                        $totalCreated++;
                    }
                }
            }
        }

        // Record audit trail event
        \App\Models\AdminActivityLog::record(
            Auth::id(),
            'bulk_seeder.fetch_completed',
            null,
            null,
            ['batches' => $this->batches, 'total_fetched' => $totalFetched, 'total_created' => $totalCreated]
        );

        session()->flash('message', "Bulk fetch finished. Fetched {$totalFetched} questions from ALOC; created {$totalCreated} new unique questions locally.");
    }

    public function render()
    {
        return view('livewire.admin.bulk-seeder')
            ->layout('layouts.app');
    }
}
