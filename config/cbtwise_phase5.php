<?php

return [
    // Affiliate
    'affiliate_commission_rate' => env('AFFILIATE_COMMISSION_RATE', 20),  // %
    'affiliate_cookie_days'     => env('AFFILIATE_COOKIE_DAYS', 30),
    'affiliate_min_payout'      => env('AFFILIATE_MIN_PAYOUT', 5000),    // NGN
    'affiliate_payout_day'      => env('AFFILIATE_PAYOUT_DAY', 1),       // Day of month

    // AI Tutor
    'ai_tutor_free_daily_limit'    => env('AI_TUTOR_FREE_LIMIT', 10),
    'ai_tutor_premium_daily_limit' => env('AI_TUTOR_PREMIUM_LIMIT', 0),  // 0 = unlimited
    'ai_tutor_model'               => env('AI_TUTOR_MODEL', 'google/gemma-3-27b-it'),
    'ai_tutor_max_tokens'          => env('AI_TUTOR_MAX_TOKENS', 800),

    // Adaptive Learning
    'irt_learning_rate' => env('IRT_LEARNING_RATE', 0.1),
    'irt_initial_theta' => env('IRT_INITIAL_THETA', 0.5),

    // Gamification
    'xp_per_correct_answer' => env('XP_PER_CORRECT', 5),
    'xp_per_exam_completed' => env('XP_PER_EXAM', 50),
    'xp_per_streak_day'     => env('XP_PER_STREAK', 10),
    'xp_per_level'          => env('XP_PER_LEVEL', 500),

    // Schools
    'school_tier_prices_ngn' => [
        'starter'    => 15000,
        'growth'     => 45000,
        'pro'        => 100000,
        'enterprise' => 0, // Custom invoice
    ],
    'school_tier_seats' => [
        'starter'    => 50,
        'growth'     => 200,
        'pro'        => 500,
        'enterprise' => 9999,
    ],

    // SEO
    'seo_min_questions_for_page' => env('SEO_MIN_QUESTIONS', 5),
    'seo_ai_cache_days'          => env('SEO_AI_CACHE_DAYS', 30),

    // Analytics ETL
    'etl_batch_size' => env('ETL_BATCH_SIZE', 1000),
];
