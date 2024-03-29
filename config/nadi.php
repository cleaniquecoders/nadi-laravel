<?php

use CleaniqueCoders\NadiLaravel\Handler\HandleCommandEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleExceptionEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleFailedJobEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleHttpRequestEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleNotificationFailedEvent;
use CleaniqueCoders\NadiLaravel\Handler\HandleQueryExecutedEvent;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Queue\Events\JobFailed;

return [
    'enabled' => env('NADI_ENABLED', true),

    'driver' => env('NADI_DRIVER', 'log'),

    'connections' => [
        'log' => [
            'path' => env('NADI_STORAGE_PATH', storage_path('nadi/')),
        ],
        'http' => [
            'key' => env('NADI_API_KEY'),
            'token' => env('NADI_APP_KEY'),
            'version' => env('NADI_VERSION', 'v1'),
            'endpoint' => env('NADI_ENDPOINT', 'https://nadi.cleaniquecoders.com/api'),
        ],
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
        NotificationFailed::class => [
            HandleNotificationFailedEvent::class,
        ],
        CommandFinished::class => [
            HandleCommandEvent::class,
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
];
