<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Http\Clients\AlocApiClient;
use App\Services\AdminLogger;
use Illuminate\Support\Facades\Auth;
use League\Csv\Reader;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BulkSeeder extends Component
{
    use WithFileUploads;

    public $batches = 3;
    public $selectedExamId = 'all';
    public $selectedSubjectId = 'all';
    public $dryRun = false;
    public $source = 'aloc';
    public $csvFile;

    public $isRunning = false;
    public $logs = [];
    public $totalScanned = 0;
    public $totalCreated = 0;
    public $totalSkippedDuplicates = 0;
    public $totalInvalid = 0;

    public function startBulkFetch()
    {
        if ($this->source === 'csv') {
            $this->importCsv();
            return;
        }

        $this->isRunning = true;
        $this->logs = [];
        $this->totalScanned = 0;
        $this->totalCreated = 0;
        $this->totalSkippedDuplicates = 0;
        $this->totalInvalid = 0;

        $alocClient = new AlocApiClient();
        
        if ($this->selectedSubjectId !== 'all') {
            // The subject picker contains subjects from every exam. When an
            // administrator picks one, it must take precedence over the exam
            // filter so a stale exam selection cannot silently produce zero work.
            $query = Subject::with('exam')->withCount('questions');
            $query->where('id', $this->selectedSubjectId);
        } else {
            $query = Subject::with('exam')->withCount('questions');
            if ($this->selectedExamId !== 'all') {
                $query->where('exam_id', $this->selectedExamId);
            }
        }

        $subjects = $query->get();

        $this->logs[] = "[" . now()->toTimeString() . "] Starting bulk import for {$subjects->count()} subject(s). Batches: {$this->batches}. Dry run: " . ($this->dryRun ? 'YES' : 'NO');

        if ($subjects->isEmpty()) {
            $this->logs[] = "[" . now()->toTimeString() . "] No subject matches the current selection. Choose a subject again or select All Subjects.";
            $this->isRunning = false;
            session()->flash('message', 'No subjects matched this import configuration. Please choose a subject again.');
            return;
        }

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
            // Avoid requesting content that is already in the local question
            // bank. This keeps multi-subject imports fast and prevents ALOC
            // rate-limit delays after a subject has reached its target.
            $needed = max(0, (int) $subject->target_question_count - (int) $subject->questions_count);
            $subjectBatches = min((int) $this->batches, (int) ceil($needed / $questionsPerBatch));
            if ($subjectBatches === 0) {
                $this->logs[] = '[' . now()->toTimeString() . "] {$subject->name}: skipped — local target already met ({$subject->questions_count} questions).";
                continue;
            }
            $availableYears = $alocClient->fetchAvailableYears($alocSubjectName, $alocExamType);

            if (empty($availableYears)) {
                $reason = $alocClient->lastError ?: 'No ALOC years are available for this subject and examination.';
                $this->logs[] = "[" . now()->toTimeString() . "] Notice: {$subject->name} could not be prepared for import. Detail: {$reason}";
                continue;
            }

            for ($b = 0; $b < $subjectBatches; $b++) {
                // ALOC's question endpoint needs an available year for reliable
                // results. Spread batches over the catalog instead of repeating
                // the same request and importing duplicate questions.
                $year = $availableYears[$b % count($availableYears)];
                try {
                    $alocQuestionsData = $alocClient->fetchQuestions(
                        $alocSubjectName,
                        $questionsPerBatch,
                        $alocExamType,
                        null,
                        $year,
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

                if ($alocClient->lastFromCache) {
                    $this->logs[] = '[' . now()->toTimeString() . "] {$subject->name} batch " . ($b + 1) . ': served from local ALOC cache.';
                }

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

    /**
     * Import a licensed, administrator-supplied question bank without any
     * dependency on a third-party API. Column headings are case-insensitive.
     */
    public function importCsv(): void
    {
        $this->validate([
            'csvFile' => ['required', 'file', 'mimes:csv,txt,zip', 'max:20480'],
        ], [
            'csvFile.required' => 'Choose a CSV file to import.',
            'csvFile.mimes' => 'The question bank must be a CSV or ZIP file.',
        ]);

        $this->isRunning = true;
        $this->logs = [];
        $this->totalScanned = 0;
        $this->totalCreated = 0;
        $this->totalSkippedDuplicates = 0;
        $this->totalInvalid = 0;

        // A full ALOC archive can contain thousands of rows. Keep the work
        // within one request practical on shared hosting and avoid repeating
        // the same lookup for every question in the archive.
        if (function_exists('set_time_limit')) {
            @set_time_limit(600);
        }
        $knownHashes = Question::query()->pluck('dedupe_hash')->filter()->flip()->all();
        $examCache = [];
        $subjectCache = [];

        $temporaryPath = null;
        $fileCount = 1;
        try {
            [$questionPath, $fileCount, $temporaryPath] = $this->prepareQuestionUpload();
            $csv = Reader::createFromPath($questionPath);
            $csv->setHeaderOffset(0);
            $headers = collect($csv->getHeader())
                ->mapWithKeys(fn ($header) => [$this->normaliseHeader($header) => $header]);

            $required = [
                'exam' => ['exam', 'exam_type'],
                'subject' => ['subject'],
                'question_text' => ['question_text', 'question'],
                'option_a' => ['option_a'], 'option_b' => ['option_b'],
                'option_c' => ['option_c'], 'option_d' => ['option_d'],
                'correct_option' => ['correct_option', 'correct_answer'],
            ];
            $missing = collect($required)->filter(
                fn (array $alternatives) => !collect($alternatives)->contains(fn ($header) => $headers->has($header))
            )->keys()->all();
            if ($missing) {
                $this->logs[] = '[' . now()->toTimeString() . '] Import stopped: missing CSV columns ' . implode(', ', $missing) . '.';
                session()->flash('message', 'CSV import needs the required column headings. Download the template and try again.');
                return;
            }

            foreach ($csv->getRecords() as $rowNumber => $rawRow) {
                $row = [];
                foreach ($headers as $normalised => $original) {
                    $row[$normalised] = trim((string) ($rawRow[$original] ?? ''));
                }

                // Accept exports from both CBTWise's template and ALOC's
                // official CSV export without requiring manual renaming.
                $row['exam'] = $row['exam'] ?? $row['exam_type'] ?? '';
                $row['question_text'] = $row['question_text'] ?? $row['question'] ?? '';
                $row['correct_option'] = $row['correct_option'] ?? $row['correct_answer'] ?? '';

                $this->totalScanned++;
                $examSlug = match (strtolower($row['exam'])) {
                    'jamb', 'utme', 'jamb utme' => 'utme',
                    'waec', 'wassce' => 'waec',
                    'neco' => 'neco',
                    'post_utme', 'post-utme', 'post utme' => 'post-utme',
                    default => strtolower($row['exam']),
                };
                $examCacheKey = strtolower($row['exam']) . '|' . $examSlug;
                $exam = $examCache[$examCacheKey] ??= Exam::query()
                    ->whereRaw('LOWER(name) = ?', [strtolower($row['exam'])])
                    ->orWhereRaw('LOWER(slug) = ?', [$examSlug])
                    ->first();
                $subjectCacheKey = ($exam?->id ?? 'missing') . '|' . strtolower($row['subject']);
                $subject = $subjectCache[$subjectCacheKey] ??= $exam
                    ? Subject::query()->where('exam_id', $exam->id)
                        ->where(function ($query) use ($row) {
                            $query->whereRaw('LOWER(name) = ?', [strtolower(str_replace('-', ' ', $row['subject']))])
                                ->orWhereRaw('LOWER(slug) = ?', [strtolower($row['subject'])]);
                        })->first()
                    : null;

                $correctOption = strtolower($row['correct_option']);
                if (!$exam || !$subject || !in_array($correctOption, ['a', 'b', 'c', 'd', 'e'], true) || $row['question_text'] === '') {
                    $this->totalInvalid++;
                    continue;
                }

                $hash = Question::dedupeHash($row['question_text']);
                if (isset($knownHashes[$hash])) {
                    $this->totalSkippedDuplicates++;
                    continue;
                }

                if (!$this->dryRun) {
                    Question::create([
                        'dedupe_hash' => $hash,
                        'exam_id' => $exam->id,
                        'subject_id' => $subject->id,
                        'created_by' => Auth::id(),
                        'year' => is_numeric($row['year'] ?? null) ? (int) $row['year'] : null,
                        'difficulty' => in_array($row['difficulty'] ?? '', ['easy', 'medium', 'hard'], true) ? $row['difficulty'] : 'medium',
                        'question_text' => $row['question_text'],
                        'option_a' => $row['option_a'],
                        'option_b' => $row['option_b'],
                        'option_c' => $row['option_c'],
                        'option_d' => $row['option_d'],
                        'option_e' => ($row['option_e'] ?? '') ?: null,
                        'correct_option' => $correctOption,
                        'explanation' => ($row['explanation'] ?? '') ?: null,
                        'source' => isset($row['exam_type']) ? 'aloc_csv' : 'csv',
                    ]);
                }

                $knownHashes[$hash] = true;
                $this->totalCreated++;
            }

            $this->logs[] = '[' . now()->toTimeString() . "] Question-bank import finished! Files: {$fileCount}, Scanned: {$this->totalScanned}, Created: {$this->totalCreated}, Duplicates skipped: {$this->totalSkippedDuplicates}, Invalid: {$this->totalInvalid}.";
            AdminLogger::log('question_bank.csv_imported', Question::class, [
                'scanned' => $this->totalScanned,
                'created' => $this->totalCreated,
                'duplicates' => $this->totalSkippedDuplicates,
                'invalid' => $this->totalInvalid,
                'dry_run' => $this->dryRun,
            ]);
            session()->flash('message', "CSV import complete. Created {$this->totalCreated} questions; skipped {$this->totalSkippedDuplicates} duplicates.");
        } catch (\Throwable $exception) {
            report($exception);
            $this->logs[] = '[' . now()->toTimeString() . '] CSV import failed: ' . $exception->getMessage();
            session()->flash('message', 'CSV import could not be completed. Check the file and its headings, then try again.');
        } finally {
            if ($temporaryPath && is_file($temporaryPath)) {
                @unlink($temporaryPath);
            }
            $this->isRunning = false;
        }
    }

    /**
     * Merge only data/*.csv entries from an uploaded ZIP into a temporary CSV.
     * Scripts, logs, metadata, .env files and macOS resource forks are ignored.
     */
    protected function prepareQuestionUpload(): array
    {
        $sourcePath = $this->csvFile->getRealPath();
        if (strtolower($this->csvFile->getClientOriginalExtension()) !== 'zip') {
            return [$sourcePath, 1, null];
        }

        if (!class_exists(\ZipArchive::class)) {
            return $this->prepareQuestionZipWithPhar($sourcePath);
        }

        $archive = new \ZipArchive();
        if ($archive->open($sourcePath) !== true) {
            throw new \RuntimeException('The ZIP archive could not be opened.');
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'cbtwise-questions-');
        $output = fopen($temporaryPath, 'wb');
        $fileCount = 0;
        $wroteHeader = false;

        try {
            for ($index = 0; $index < $archive->numFiles; $index++) {
                $entryName = str_replace('\\', '/', $archive->getNameIndex($index));
                if (str_starts_with($entryName, '__MACOSX/') || str_starts_with(basename($entryName), '._') || !preg_match('#(?:^|/)data/[^/]+\.csv$#i', $entryName)) {
                    continue;
                }

                $input = $archive->getStream($archive->getNameIndex($index));
                if (!$input) {
                    continue;
                }

                $rowIndex = 0;
                while (($row = fgetcsv($input)) !== false) {
                    if ($rowIndex++ === 0) {
                        if (!$wroteHeader) {
                            fputcsv($output, $row);
                            $wroteHeader = true;
                        }
                        continue;
                    }
                    fputcsv($output, $row);
                }
                fclose($input);
                $fileCount++;
            }
        } finally {
            fclose($output);
            $archive->close();
        }

        if (!$wroteHeader || $fileCount === 0) {
            @unlink($temporaryPath);
            throw new \RuntimeException('No question CSV files were found inside the ZIP data folder.');
        }

        return [$temporaryPath, $fileCount, $temporaryPath];
    }

    /** Fallback for shared hosts where the optional ext-zip module is disabled. */
    protected function prepareQuestionZipWithPhar(string $sourcePath): array
    {
        $archive = new \PharData($sourcePath);
        $temporaryPath = tempnam(sys_get_temp_dir(), 'cbtwise-questions-');
        $output = fopen($temporaryPath, 'wb');
        $fileCount = 0;
        $wroteHeader = false;

        try {
            foreach (new \RecursiveIteratorIterator($archive) as $entry) {
                $entryName = str_replace('\\', '/', $entry->getPathname());
                if (!$entry->isFile() || str_contains($entryName, '/__MACOSX/') || str_starts_with(basename($entryName), '._') || !preg_match('#(?:^|/)data/[^/]+\.csv$#i', $entryName)) {
                    continue;
                }
                $input = fopen($entry->getPathname(), 'rb');
                $rowIndex = 0;
                while (($row = fgetcsv($input)) !== false) {
                    if ($rowIndex++ === 0) {
                        if (!$wroteHeader) {
                            fputcsv($output, $row);
                            $wroteHeader = true;
                        }
                        continue;
                    }
                    fputcsv($output, $row);
                }
                fclose($input);
                $fileCount++;
            }
        } finally {
            fclose($output);
        }

        if (!$wroteHeader || $fileCount === 0) {
            @unlink($temporaryPath);
            throw new \RuntimeException('No question CSV files were found inside the ZIP data folder.');
        }

        return [$temporaryPath, $fileCount, $temporaryPath];
    }

    public function downloadCsvTemplate(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['exam', 'subject', 'year', 'difficulty', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_option', 'explanation']);
            fputcsv($output, ['JAMB UTME', 'Biology', now()->year, 'medium', 'Which organelle produces energy for the cell?', 'Nucleus', 'Mitochondrion', 'Ribosome', 'Cell wall', '', 'b', 'Mitochondria release usable energy during cellular respiration.']);
            fclose($output);
        }, 'cbtwise-question-import-template.csv', ['Content-Type' => 'text/csv']);
    }

    protected function normaliseHeader(string $header): string
    {
        return strtolower(trim(str_replace([' ', '-'], '_', $header)));
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
