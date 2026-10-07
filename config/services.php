<?php

return [
    'ai' => [
        'url' => env('AI_SERVICE_URL', 'http://127.0.0.1:8001'),
        'timeout' => (int) env('AI_SERVICE_TIMEOUT', 30),
        'allow_demo_fallback' => filter_var(
            env('AI_ALLOW_DEMO_FALLBACK', false),
            FILTER_VALIDATE_BOOL
        ),
        'high_confidence' => (float) env('AI_CONFIDENCE_HIGH', 0.75),
        'low_confidence' => (float) env('AI_CONFIDENCE_LOW', 0.50),
    ],

    'realtime' => [
        'url' => env(
            'REALTIME_SERVICE_URL',
            'http://127.0.0.1:3001'
        ),
    ],
];