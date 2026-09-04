<?php

namespace App\Jobs;

use App\Models\ExamSession;
use App\Models\ExamAnswer;
use App\Models\AiLog;
use App\Services\OpenAiClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class StudyPlanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue = 'ai';

    protected int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(OpenAiClient $client): void
    {
        // Set status to generating
        Cache::put("study-plan-status:{$this->userId}", 'generating', 600);

        // 1. Enforce rate limit (10 per hour)
        $rateLimitKey = 'ai-rate-limit:' . $this->userId;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            Cache::put("study-plan-status:{$this->userId}", 'rate_limited', 600);
            return;
        }

        // 2. Fetch last 10 submitted sessions
        $sessions = ExamSession::where('user_id', $this->userId)
            ->where('status', 'submitted')
            ->with('exam')
            ->latest('submitted_at')
            ->take(10)
            ->get();

        if ($sessions->isEmpty()) {
            Cache::put("study-plan-status:{$this->userId}", 'no_data', 600);
            return;
        }

        // 3. Aggregate subject/topic performance
        $sessionIds = $sessions->pluck('id');
        $answers = ExamAnswer::whereIn('exam_session_id', $sessionIds)
            ->with(['question.subject', 'question.topic'])
            ->get();

        $topicStats = [];
        $subjectStats = [];

        foreach ($answers as $ans) {
            $question = $ans->question;
            if (!$question) continue;

            $subjectName = $question->subject->name ?? 'Unknown Subject';
            $topicName = $question->topic->name ?? 'General Practice';

            // Subject aggregate
            if (!isset($subjectStats[$subjectName])) {
                $subjectStats[$subjectName] = ['correct' => 0, 'total' => 0];
            }
            $subjectStats[$subjectName]['total']++;
            if ($ans->is_correct) {
                $subjectStats[$subjectName]['correct']++;
            }

            // Topic aggregate
            $topicKey = "{$subjectName} > {$topicName}";
            if (!isset($topicStats[$topicKey])) {
                $topicStats[$topicKey] = ['correct' => 0, 'total' => 0];
            }
            $topicStats[$topicKey]['total']++;
            if ($ans->is_correct) {
                $topicStats[$topicKey]['correct']++;
            }
        }

        // Calculate accuracies
        $weakTopics = [];
        foreach ($topicStats as $topic => $stats) {
            $accuracy = ($stats['correct'] / $stats['total']) * 100;
            if ($accuracy < 60) {
                $weakTopics[] = [
                    'topic' => $topic,
                    'accuracy' => round($accuracy, 1),
                    'total_questions' => $stats['total'],
                ];
            }
        }

        // Sort weak topics by accuracy ascending
        usort($weakTopics, fn($a, $b) => $a['accuracy'] <=> $b['accuracy']);
        // Take top 5 weakest topics
        $weakTopics = array_slice($weakTopics, 0, 5);

        // Prepare summaries for OpenAI
        $sessionsData = [];
        foreach ($sessions as $sess) {
            $sessionsData[] = [
                'exam' => $sess->exam->name ?? 'CBT Exam',
                'score' => $sess->score,
                'subjects' => collect($sess->score_breakdown ?? [])->keys()->toArray(),
                'weak_topics' => collect($weakTopics)->pluck('topic')->toArray(),
            ];
        }

        // 4. Request OpenAI Study Plan
        $result = $client->generateStudyPlan($sessionsData);

        if ($result) {
            // Save plan to cache
            Cache::put("study-plan:{$this->userId}", $result['content'], now()->addDays(7));
            Cache::put("study-plan-status:{$this->userId}", 'ready', now()->addDays(7));

            // Log token usage
            AiLog::create([
                'user_id' => $this->userId,
                'feature' => 'study_plan',
                'prompt_tokens' => $result['prompt_tokens'],
                'completion_tokens' => $result['completion_tokens'],
                'total_tokens' => $result['total_tokens'],
            ]);

            // Increment rate limiter
            RateLimiter::hit($rateLimitKey, 3600);
        } else {
            Cache::put("study-plan-status:{$this->userId}", 'failed', 600);
        }
    }
}
