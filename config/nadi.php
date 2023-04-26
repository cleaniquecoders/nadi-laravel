<?php

use CleaniqueCoders\NadiLaravel\Handler\HandleExceptionEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleFailedJobEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleQueryExecutedEvent;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Queue\Events\JobFailed;

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
        QueryExecuted::class => [
            HandleQueryExecutedEvent::class,
        ],
        JobFailed::class => [
            HandleFailedJobEvent::class,
        ],
    ],

    'query' => [
        'slow-threshold' => env('NADI_QUERY_SLOW_THRESHOLD', 500), // in miliseconds.
    ],
];
