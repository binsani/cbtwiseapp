<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncOfflineQuestionBank extends Command
{
    protected $signature = 'offline:sync-question-bank {template : Absolute path to the bundled SQLite database}';

    protected $description = 'Merge the bundled offline question bank into the local desktop database without deleting learner data';

    public function handle(): int
    {
        if (config('database.default') !== 'sqlite') {
            $this->error('The offline question-bank sync requires SQLite.');

            return self::FAILURE;
        }

        $template = $this->argument('template');
        if (!is_file($template)) {
            $this->error('Bundled question-bank database was not found.');

            return self::FAILURE;
        }

        $quotedTemplate = str_replace("'", "''", $template);
        DB::statement("ATTACH DATABASE '{$quotedTemplate}' AS bundled_bank");

        try {
            $examIds = Exam::query()->pluck('id', 'slug')->all();
            $subjectIds = Subject::query()
                ->get(['id', 'exam_id', 'slug'])
                ->mapWithKeys(fn (Subject $subject) => [
                    ($subject->exam?->slug ?? Exam::find($subject->exam_id)?->slug) . '|' . $subject->slug => $subject->id,
                ])->all();

            $created = 0;
            $updated = 0;

            DB::table('bundled_bank.questions as question')
                ->join('bundled_bank.exams as exam', 'exam.id', '=', 'question.exam_id')
                ->join('bundled_bank.subjects as subject', 'subject.id', '=', 'question.subject_id')
                ->orderBy('question.id')
                ->select([
                    'question.*',
                    'exam.slug as exam_slug',
                    'subject.slug as subject_slug',
                ])
                ->chunk(250, function ($questions) use (&$created, &$updated, $examIds, $subjectIds): void {
                    $hashes = $questions->pluck('dedupe_hash')->filter()->all();
                    $existing = Question::query()->whereIn('dedupe_hash', $hashes)->pluck('id', 'dedupe_hash')->all();
                    $rows = [];

                    foreach ($questions as $question) {
                        $examId = $examIds[$question->exam_slug] ?? null;
                        $subjectId = $subjectIds[$question->exam_slug . '|' . $question->subject_slug] ?? null;
                        if (!$examId || !$subjectId) {
                            continue;
                        }

                        isset($existing[$question->dedupe_hash]) ? $updated++ : $created++;
                        $rows[] = [
                            'dedupe_hash' => $question->dedupe_hash,
                            'exam_id' => $examId,
                            'subject_id' => $subjectId,
                            'topic_id' => null,
                            'created_by' => null,
                            'year' => $question->year,
                            'difficulty' => $question->difficulty,
                            'question_text' => $question->question_text,
                            'question_image' => $question->question_image,
                            'option_a' => $question->option_a,
                            'option_b' => $question->option_b,
                            'option_c' => $question->option_c,
                            'option_d' => $question->option_d,
                            'option_e' => $question->option_e,
                            'correct_option' => $question->correct_option,
                            'explanation' => $question->explanation,
                            'source' => $question->source,
                            'times_served' => 0,
                            'times_correct' => 0,
                            'reports_count' => 0,
                            'is_flagged' => false,
                            'created_at' => $question->created_at,
                            'updated_at' => $question->updated_at,
                        ];
                    }

                    if ($rows) {
                        Question::upsert(
                            $rows,
                            ['dedupe_hash'],
                            [
                                'exam_id', 'subject_id', 'year', 'difficulty', 'question_text', 'question_image',
                                'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_option',
                                'explanation', 'source', 'updated_at',
                            ]
                        );
                    }
                });

            $this->info("Offline question bank synchronised: {$created} added, {$updated} already present.");

            return self::SUCCESS;
        } finally {
            DB::statement('DETACH DATABASE bundled_bank');
        }
    }
}
