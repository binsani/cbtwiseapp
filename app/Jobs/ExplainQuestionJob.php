<?php

namespace App\Jobs;

use App\Models\Question;
use App\Models\AiLog;
use App\Services\OpenAiClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\RateLimiter;

class ExplainQuestionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue = 'ai';

    protected int $questionId;
    protected int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $questionId, int $userId)
    {
        $this->questionId = $questionId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(OpenAiClient $client): void
    {
        $question = Question::find($this->questionId);
        if (!$question || $question->explanation) {
            return;
        }

        // 1. Enforce rate limit (10 per hour)
        $rateLimitKey = 'ai-rate-limit:' . $this->userId;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return;
        }

        // 2. Fetch explanation from OpenAI
        $options = [
            'a' => $question->option_a,
            'b' => $question->option_b,
            'c' => $question->option_c,
            'd' => $question->option_d,
        ];
        if ($question->option_e) {
            $options['e'] = $question->option_e;
        }

        $result = $client->getQuestionExplanation(
            $question->question_text,
            $options,
            $question->correct_option
        );

        if ($result) {
            // Update question explanation
            $question->update([
                'explanation' => $result['content'],
            ]);

            // Log token usage
            AiLog::create([
                'user_id' => $this->userId,
                'feature' => 'explanation',
                'prompt_tokens' => $result['prompt_tokens'],
                'completion_tokens' => $result['completion_tokens'],
                'total_tokens' => $result['total_tokens'],
            ]);

            // Increment rate limiter
            RateLimiter::hit($rateLimitKey, 3600);
        }
    }
}
