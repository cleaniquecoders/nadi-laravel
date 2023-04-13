<?php

namespace CleaniqueCoders\NadiLaravel\Collector;

class Metric
{
    public static function getCurrentRequest(): array
    {
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : request()->server('REQUEST_TIME_FLOAT');

        return [
            'app.controller.action' => optional(request()->route())->getActionName(),
            'app.middleware' => array_values(optional(request()->route())->gatherMiddleware() ?? []),

            'http.client.duration' => $startTime ? floor((microtime(true) - $startTime) * 1000) : null,

            'http.scheme' => request()->getScheme(),
            'http.route' => request()->getRequestUri(),
            'http.method' => request()->getMethod(),
            'http.status_code' => http_response_code(),
            'http.query' => request()->getQueryString(),
            'http.uri' => str_replace(request()->root(), '', request()->fullUrl()) ?: '/',
            'http.headers' => collect(request()->headers->all())
                            ->map(function ($header) {
                                return $header[0];
                            })
                            ->reject(function ($header, $key) {
                                return in_array($key, [
                                    'authorization', config('nadi.header-key'), 'nadi-key',
                                ]);
                            })
                            ->toArray(),
            'net.host.name' => request()->getHost(),
            'net.host.port' => request()->getPort(),
            'net.protocol.name' => app()->runningInConsole() ? 'CLI' : 'HTTP',
            'net.protocol.version' => ! app()->runningInConsole() ? request()->getProtocolVersion() : '',

            'system.server.cpu' => sys_getloadavg(),
            'system.server.memory.peak' => memory_get_peak_usage(true),
            'system.server.memory.usage' => memory_get_usage(true),
            'system.server.storage' => disk_total_space(base_path()),
        ];
    }
}
