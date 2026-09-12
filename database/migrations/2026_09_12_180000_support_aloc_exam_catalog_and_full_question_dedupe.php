<?php

use App\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('exams')->updateOrInsert(
            ['slug' => 'post-utme'],
            [
                'name' => 'Post-UTME',
                'description' => 'University post-UTME practice examination',
                'duration_minutes_default' => 90,
                'questions_per_subject_default' => 40,
                'is_active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $postUtmeId = DB::table('exams')->where('slug', 'post-utme')->value('id');
        $waecId = DB::table('exams')->where('slug', 'waec')->value('id');

        $subjects = [
            'financial-accounting' => ['Financial Accounting', 'credit-card'],
            'christian-religious-studies' => ['Christian Religious Studies', 'bible'],
            'economics' => ['Economics', 'trending-up'],
            'geography' => ['Geography', 'globe'],
            'government' => ['Government', 'landmark'],
            'literature-in-english' => ['Literature in English', 'book'],
            'mathematics' => ['Mathematics', 'calculator'],
            'physics' => ['Physics', 'atom'],
        ];

        $sortOrder = 1;
        foreach ($subjects as $slug => $subject) {
            DB::table('subjects')->updateOrInsert(
                ['exam_id' => $postUtmeId, 'slug' => $slug],
                [
                    'name' => $subject[0],
                    'icon' => $subject[1],
                    'sort_order' => $sortOrder++,
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        if ($waecId) {
            foreach ([
                'history' => ['History', 'landmark'],
                'insurance' => ['Insurance', 'shield'],
            ] as $slug => $subject) {
                DB::table('subjects')->updateOrInsert(
                    ['exam_id' => $waecId, 'slug' => $slug],
                    [
                        'name' => $subject[0],
                        'icon' => $subject[1],
                        'sort_order' => 99,
                        'is_active' => true,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }

        $seen = [];
        DB::table('questions')->orderBy('id')->select(['id', 'question_text'])->lazyById(250)->each(
            function (object $question) use (&$seen): void {
                $hash = Question::dedupeHash($question->question_text);

                // Retain historical exact duplicates safely; the first row
                // keeps the canonical full-content hash for future imports.
                if (isset($seen[$hash])) {
                    $hash = hash('sha256', $hash . '|legacy-duplicate|' . $question->id);
                }
                $seen[$hash] = true;

                DB::table('questions')->where('id', $question->id)->update([
                    'dedupe_hash' => $hash,
                ]);
            }
        );
    }

    public function down(): void
    {
        // Existing question hashes are intentionally not reverted: the newer
        // full-content keys are safer and compatible with all app versions.
    }
};
