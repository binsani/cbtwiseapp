<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'English Language' => 'book-open',
            'Mathematics' => 'calculator',
            'Physics' => 'atom',
            'Chemistry' => 'flask',
            'Biology' => 'dna',
            'Economics' => 'trending-up',
            'Government' => 'landmark',
            'Literature in English' => 'book',
            'Christian Religious Studies' => 'bible',
            'Islamic Religious Studies' => 'moon',
            'Geography' => 'globe',
            'Agricultural Science' => 'leaf',
            'Commerce' => 'shopping-cart',
            'Financial Accounting' => 'credit-card',
            'Civic Education' => 'shield',
        ];

        $exams = Exam::all();

        foreach ($exams as $exam) {
            $order = 1;
            foreach ($subjects as $name => $icon) {
                Subject::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'slug' => Str::slug($name),
                    ],
                    [
                        'name' => $name,
                        'icon' => $icon,
                        'sort_order' => $order++,
                    ]
                );
            }
        }
    }
}
