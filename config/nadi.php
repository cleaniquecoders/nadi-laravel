<?php

use CleaniqueCoders\NadiLaravel\Handler\HandleExceptionEvent;
use Illuminate\Log\Events\MessageLogged;

return [
    'endpoint' => env('NADI_ENDPOINT', 'https://127.0.0.1:8000/api'),

    'enabled' => env('NADI_ENABLED', true),

    'driver' => env('NADI_DRIVER', 'log'),

    'key' => env('NADI_KEY'),

    'token' => env('NADI_TOKEN'),

    'version' => env('NADI_VERSION', 'v1'),

    'observe' => [
        MessageLogged::class => [
            HandleExceptionEvent::class,
        ],
    ],
];
