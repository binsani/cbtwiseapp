<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        Exam::updateOrCreate(
            ['slug' => 'utme'],
            [
                'name' => 'JAMB UTME',
                'description' => 'Unified Tertiary Matriculation Examination',
                'duration_minutes_default' => 120,
                'questions_per_subject_default' => 40,
                'is_active' => true,
            ]
        );

        Exam::updateOrCreate(
            ['slug' => 'waec'],
            [
                'name' => 'WAEC SSCE',
                'description' => 'West African Examinations Council School Certificate',
                'duration_minutes_default' => 120,
                'questions_per_subject_default' => 50,
                'is_active' => true,
            ]
        );

        Exam::updateOrCreate(
            ['slug' => 'neco'],
            [
                'name' => 'NECO SSCE',
                'description' => 'National Examinations Council School Certificate',
                'duration_minutes_default' => 120,
                'questions_per_subject_default' => 50,
                'is_active' => true,
            ]
        );
    }
}
