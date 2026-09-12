<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Http\Clients\AlocApiClient;
use App\Services\AdminLogger;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BulkSeeder extends Component
{
    public $batches = 3;
    public $selectedExamId = 'all';
    public $selectedSubjectId = 'all';
    public $dryRun = false;

    public $isRunning = false;
    public $logs = [];
    public $totalScanned = 0;
    public $totalCreated = 0;
    public $totalSkippedDuplicates = 0;
    public $totalInvalid = 0;

    public function startBulkFetch()
    {
        $this->isRunning = true;
        $this->logs = [];
        $this->totalScanned = 0;
        $this->totalCreated = 0;
        $this->totalSkippedDuplicates = 0;
        $this->totalInvalid = 0;

        $alocClient = new AlocApiClient();
        
        $query = Subject::with('exam')->where('is_active', true);
        if ($this->selectedExamId !== 'all') {
            $query->where('exam_id', $this->selectedExamId);
        }
        if ($this->selectedSubjectId !== 'all') {
            $query->where('id', $this->selectedSubjectId);
        }

        $subjects = $query->get();

        $this->logs[] = "[" . now()->toTimeString() . "] Starting bulk import for {$subjects->count()} subject(s). Batches: {$this->batches}. Dry run: " . ($this->dryRun ? 'YES' : 'NO');

        $questionsPerBatch = 15;

        @set_time_limit(300);

        foreach ($subjects as $subject) {
            $alocSubjectName = $subject->name;
            $alocExamType = match ($subject->exam?->slug) {
                'utme' => 'jamb',
                'waec' => 'waec',
                'neco' => 'neco',
                default => null,
            };
            $subjectCreated = 0;
            $subjectDupes = 0;
            $cursor = null;

            for ($b = 0; $b < $this->batches; $b++) {
                try {
                    $alocQuestionsData = $alocClient->fetchQuestions(
                        $alocSubjectName,
                        $questionsPerBatch,
                        $alocExamType,
                        $cursor,
                    );
                } catch (\Exception $e) {
                    $this->logs[] = "[" . now()->toTimeString() . "] Warning: Failed batch " . ($b+1) . " for {$subject->name}: " . $e->getMessage();
                    continue;
                }

                if (empty($alocQuestionsData)) {
                    $reason = $alocClient->lastError ?: "No questions returned";
                    $ep = $alocClient->lastEndpoint ? " [{$alocClient->lastEndpoint}]" : "";
                    $this->logs[] = "[" . now()->toTimeString() . "] Notice: 0 questions for {$subject->name} ({$alocSubjectName}){$ep}. Detail: {$reason}";
                    break;
                }

                $cursor = $alocClient->lastNextCursor;

                foreach ($alocQuestionsData as $item) {
                    $questionText = $item['question'] ?? $item['question_text'] ?? '';
                    if (empty($questionText)) {
                        $this->totalInvalid++;
                        continue;
                    }

                    $this->totalScanned++;

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
                        $this->totalInvalid++;
                        continue;
                    }

                    $hash = Question::dedupeHash($questionText);
                    $exists = Question::where('dedupe_hash', $hash)->exists();

                    if ($exists) {
                        $this->totalSkippedDuplicates++;
                        $subjectDupes++;
                    } else {
                        if (!$this->dryRun) {
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
                        }
                        $this->totalCreated++;
                        $subjectCreated++;
                    }
                }

                if (!$cursor) {
                    $this->logs[] = "[" . now()->toTimeString() . "] {$subject->name}: no further ALOC pages available.";
                    break;
                }
            }

            $this->logs[] = "[" . now()->toTimeString() . "] {$subject->name}: +{$subjectCreated} imported, {$subjectDupes} duplicate(s) skipped.";
        }

        $this->logs[] = "[" . now()->toTimeString() . "] Bulk seeder finished! Scanned: {$this->totalScanned}, Created: {$this->totalCreated}, Duplicates skipped: {$this->totalSkippedDuplicates}.";

        AdminLogger::log(
            'bulk_seeder.run',
            null,
            [
                'batches' => $this->batches,
                'scanned' => $this->totalScanned,
                'created' => $this->totalCreated,
                'duplicates' => $this->totalSkippedDuplicates,
                'dry_run' => $this->dryRun,
            ]
        );

        $this->isRunning = false;
        session()->flash('message', "Bulk run complete. Processed {$this->totalScanned} items; created {$this->totalCreated} new questions.");
    }

    public function render()
    {
        $exams = Exam::all();
        $subjects = Subject::with('exam')->orderBy('name')->get();

        // Subjects with very low coverage (< 50 questions)
        $lowCoverageSubjects = Subject::with('exam')
            ->withCount('questions')
            ->get()
            ->filter(fn($subject) => $subject->questions_count < 50)
            ->sortBy('questions_count')
            ->values();

        return view('livewire.admin.bulk-seeder', [
            'exams' => $exams,
            'subjects' => $subjects,
            'lowCoverageSubjects' => $lowCoverageSubjects,
        ])->layout('layouts.app');
    }
}
