<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiClient
{
    protected ?string $apiKey;
    protected string $model;
    protected int $maxTokens;

    public function __construct()
    {
        $this->apiKey = config('cbtwise.openai.api_key');
        $this->model = config('cbtwise.openai.model', 'gpt-4o-mini');
        $this->maxTokens = (int) config('cbtwise.openai.max_tokens', 500);
    }

    /**
     * Call the chat completion endpoint.
     */
    protected function callChatCompletion(array $messages): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('OpenAI API Key is not configured.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => 0.5,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'content' => $result['choices'][0]['message']['content'] ?? '',
                    'prompt_tokens' => $result['usage']['prompt_tokens'] ?? 0,
                    'completion_tokens' => $result['usage']['completion_tokens'] ?? 0,
                    'total_tokens' => $result['usage']['total_tokens'] ?? 0,
                ];
            }

            Log::error('OpenAI Chat Completion Failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('OpenAI API Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get explanation for a question.
     */
    public function getQuestionExplanation(string $questionText, array $options, string $correctOption): ?array
    {
        $optionsStr = '';
        foreach ($options as $key => $val) {
            $optionsStr .= strtoupper($key) . ") " . $val . "\n";
        }

        $systemPrompt = "You are a professional educational tutor preparing high school students in Nigeria for exams like JAMB UTME, WAEC, and NECO. "
                      . "Explain the given question in detail, why the correct option is right, and why the other options are wrong. "
                      . "Keep your tone encouraging, clear, and educational. Format using Markdown. Keep the explanation under 250 words.";

        $userPrompt = "Question: {$questionText}\n\n"
                    . "Options:\n{$optionsStr}\n"
                    . "Correct Option: " . strtoupper($correctOption);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        return $this->callChatCompletion($messages);
    }

    /**
     * Generate 7-day study plan from last 10 session summaries.
     */
    public function generateStudyPlan(array $sessionsData): ?array
    {
        $sessionHistoryStr = '';
        foreach ($sessionsData as $index => $sess) {
            $num = $index + 1;
            $sessionHistoryStr .= "Session #{$num}: Exam: {$sess['exam']}, Subjects: " . implode(', ', $sess['subjects']) . ", Score: {$sess['score']}%\n";
            $sessionHistoryStr .= "Weak Topics: " . implode(', ', $sess['weak_topics']) . "\n\n";
        }

        $systemPrompt = "You are an AI Study Coach. Based on the student's recent practice sessions, identify their weaker areas and create a structured 7-day study plan. "
                      . "Address the weak topics specifically. Provide actionable, concise advice for each day. "
                      . "Use Markdown headers and bullet points. Make it sound professional, structured, and helpful.";

        $userPrompt = "Here is my recent practice history:\n\n" . $sessionHistoryStr . "\nPlease generate my 7-day study plan.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        return $this->callChatCompletion($messages);
    }
}
