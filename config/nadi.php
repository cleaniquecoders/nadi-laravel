<?php

use CleaniqueCoders\NadiLaravel\Handler\HandleExceptionEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleFailedJobEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleHttpRequestEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleQueryExecutedEvent;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Queue\Events\JobFailed;

return [
    'enabled' => env('NADI_ENABLED', true),

    'driver' => env('NADI_DRIVER', 'log'),

    'connections' => [
        'log' => [
            'path' => storage_path('logs/'),
        ],
        'http' => [
            'key' => env('NADI_KEY'),
            'token' => env('NADI_TOKEN'),
            'version' => env('NADI_VERSION', 'v1'),
            'endpoint' => env('NADI_ENDPOINT', 'https://nadi.cleaniquecoders.com/api')
        ]
    ],

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
        RequestHandled::class => [
            HandleHttpRequestEvent::class,
        ],
    ],

    'query' => [
        'slow-threshold' => env('NADI_QUERY_SLOW_THRESHOLD', 500), // in miliseconds.
    ],

    'http' => [
        'hidden_request_headers' => [
            'authorization',
            'php-auth-pw',
        ],
        'hidden_parameters' => [
            'password',
            'password_confirmation',
        ],
        'hidden_response_parameters' => [],
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
        'ignored_status_codes' => [
            100, 101, 102, 103,
            200, 201, 202, 203, 204, 205, 206, 207,
            300, 302, 303, 304, 305, 306, 307, 308,
        ],
    ],

    'logger' => [
        'driver' => 'daily',
        'path' => storage_path('logs/nadi.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
];
