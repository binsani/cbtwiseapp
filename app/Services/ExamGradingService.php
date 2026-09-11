<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class ExamGradingService
{
    /**
     * Atomically grade an exam session, compute subject breakdowns,
     * scale scores, and update user statistics.
     *
     * @param ExamSession $session
     * @return ExamSession
     */
    public function grade(ExamSession $session): ExamSession
    {
        return DB::transaction(function () use ($session) {
            // Lock session row to prevent race conditions from concurrent clicks / timeouts
            /** @var ExamSession|null $lockedSession */
            $lockedSession = ExamSession::where('id', $session->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedSession) {
                return $session;
            }

            // If session is already finalized, return immediately (idempotent)
            if ($lockedSession->status === 'submitted') {
                return $lockedSession;
            }

            // Eager-load all answers with associated question and subject in a single query (N+1 elimination)
            $answers = ExamAnswer::with(['question.subject'])
                ->where('exam_session_id', $lockedSession->id)
                ->get();

            $totalQuestions = $answers->count();
            $correctCount = 0;
            $subjectCorrects = [];
            $subjectTotals = [];
            $correctQuestionIds = [];
            $subjectModels = [];

            foreach ($answers as $ans) {
                $question = $ans->question;
                if (!$question) {
                    continue;
                }

                $subId = (int) $question->subject_id;
                $subjectTotals[$subId] = ($subjectTotals[$subId] ?? 0) + 1;

                // Cache subject model reference from relationship
                if (!isset($subjectModels[$subId]) && $question->subject) {
                    $subjectModels[$subId] = $question->subject;
                }

                $isCorrect = (
                    $ans->selected_option !== null &&
                    strtolower((string) $question->correct_option) === strtolower((string) $ans->selected_option)
                );

                if ($isCorrect) {
                    $correctCount++;
                    $correctQuestionIds[] = $question->id;
                    $subjectCorrects[$subId] = ($subjectCorrects[$subId] ?? 0) + 1;
                }

                // Update answer record if status changed
                if ($ans->is_correct !== $isCorrect) {
                    $ans->update(['is_correct' => $isCorrect]);
                }
            }

            // Bulk increment times_correct counter for all correct questions in a single query
            if (!empty($correctQuestionIds)) {
                Question::whereIn('id', array_unique($correctQuestionIds))->increment('times_correct');
            }

            // Build granular subject-by-subject score breakdown
            $breakdown = [];
            foreach ($subjectTotals as $subId => $total) {
                $correct = $subjectCorrects[$subId] ?? 0;
                $subjectName = $subjectModels[$subId]->name ?? Subject::where('id', $subId)->value('name') ?? 'Subject';

                $breakdown[$subId] = [
                    'subject_name' => $subjectName,
                    'correct' => $correct,
                    'total' => $total,
                    'percentage' => $total > 0 ? round(($correct / $total) * 100, 2) : 0.0,
                ];
            }

            // Calculate scaled score based on examination type
            $exam = $lockedSession->exam;
            $score = 0.0;

            if ($exam && strtolower((string) $exam->slug) === 'utme') {
                // JAMB / UTME standard scaling (out of 400)
                $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 400, 2) : 0.0;
            } else {
                // Standard percentage scaling (out of 100)
                $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0.0;
            }

            // Finalize session status
            $lockedSession->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'correct_count' => $correctCount,
                'score' => $score,
                'score_breakdown' => $breakdown,
            ]);

            // Update candidate study streak
            $user = $lockedSession->user;
            if ($user && method_exists($user, 'updateStreak')) {
                $user->updateStreak();
            }

            return $lockedSession->fresh();
        });
    }
}
