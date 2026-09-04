<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CBTWise Application Configuration
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'CBTWise'),

    // ── Exam / Daily Limits ───────────────────────────────────────────────────
    'free_daily_limit'     => env('FREE_DAILY_LIMIT', 20),
    'daily_reset_timezone' => 'Africa/Lagos',

    // ── Paystack ──────────────────────────────────────────────────────────────
    'paystack' => [
        'public_key'   => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key'   => env('PAYSTACK_SECRET_KEY'),
        'payment_url'  => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
        'plan_monthly' => env('PAYSTACK_PLAN_MONTHLY'),
        'plan_annual'  => env('PAYSTACK_PLAN_ANNUAL'),
    ],

    // ── OpenAI ────────────────────────────────────────────────────────────────
    'openai' => [
        'api_key'    => env('OPENAI_API_KEY'),
        'model'      => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'max_tokens' => (int) env('OPENAI_MAX_TOKENS', 500),
    ],

    // ── ALOC Questions API ────────────────────────────────────────────────────
    'aloc' => [
        'token'   => env('ALOC_API_TOKEN'),
        'base'    => env('ALOC_API_BASE', 'https://questions.aloc.com.ng/api/v2'),
        'timeout' => (float) env('ALOC_TIMEOUT', 3),
        'retry'   => (int) env('ALOC_RETRY', 1),
    ],

    // ── Tawk.to Live Chat ─────────────────────────────────────────────────────
    'tawkto' => [
        'widget_id' => env('TAWKTO_WIDGET_ID'),
    ],

    // ── Nigerian States ───────────────────────────────────────────────────────
    'states' => [
        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa',
        'Benue', 'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo',
        'Ekiti', 'Enugu', 'FCT – Abuja', 'Gombe', 'Imo', 'Jigawa',
        'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara',
        'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun',
        'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara',
    ],

    // ── Exam Years ────────────────────────────────────────────────────────────
    'exam_years' => range(date('Y') + 1, 2000),

    // ── Purchase Code Prefix ──────────────────────────────────────────────────
    'purchase_code_prefix' => 'CBT',

    // ── Dedupe Hash Length ────────────────────────────────────────────────────
    'dedupe_char_length' => 60,

    // ── UTME Score Scaling ────────────────────────────────────────────────────
    'utme_max_score' => 400,

    // ── WAEC / NECO Grade Thresholds ─────────────────────────────────────────
    'waec_grades' => [
        'A1' => [75, 100],
        'A2' => [70, 74],
        'B2' => [65, 69],
        'B3' => [60, 64],
        'C4' => [55, 59],
        'C5' => [50, 54],
        'C6' => [45, 49],
        'D7' => [40, 44],
        'E8' => [35, 39],
        'F9' => [0,  34],
    ],
];
