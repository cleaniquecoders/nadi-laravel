<?php

namespace CleaniqueCoders\NadiLaravel\Collector;

use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Str;

class Metric
{
    public static function getCurrentRequest(): array
    {
        return array_merge(
            self::getOs(),
            self::getApp(),
            self::getNetwork(),
            self::getRuntime(),
            self::getBrowser(),
            self::getFramework(),
            self::getHttp(),
            self::getSystem(),
        );
    }

    public static function getOs(): array
    {
        return [
            'os.name' => \php_uname('s'),
            'os.hostname' => \php_uname('n'),
            'os.release' => \php_uname('r'),
            'os.family' => PHP_OS_FAMILY,
            'os.version' => \php_uname('v'),
            'os.type' => \php_uname('m'),
        ];
    }

    public static function getApp(): array
    {
        return [
            'app.controller.action' => optional(request()->route())->getActionName(),
            'app.middleware' => array_values(optional(request()->route())->gatherMiddleware() ?? []),
            'app.environment' => app()->environment(),
        ];
    }

    public static function getNetwork(): array
    {
        return [
            'net.host.name' => request()->getHost(),
            'net.host.port' => request()->getPort(),
            'net.protocol.name' => app()->runningInConsole() ? 'CLI' : 'HTTP',
            'net.protocol.version' => ! app()->runningInConsole() ? request()->getProtocolVersion() : '',
        ];
    }

    public static function getRuntime(): array
    {
        return [
            'runtime.name' => 'PHP',
            'runtime.version' => \phpversion(),
        ];
    }

    public static function getBrowser(): array
    {
        $browser = (new Browser(null, request()))->detect()->toArray();
        foreach ($browser as $key => $value) {
            unset($browser[$key]);
            $key = str_replace(['browser', 'is'], '', $key);
            $key = Str::snake($key, '.');
            $key = str_replace('i.e', 'ie', $key);
            $browser[$key] = $value;
        }

        return [
            'browser' => $browser,
        ];
    }

    public static function getFramework(): array
    {
        return [
            'framework.name' => 'Laravel',
            'framework.version' => app()->version(),
        ];
    }

    public static function getHttp(): array
    {
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : request()->server('REQUEST_TIME_FLOAT');

        return [
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
        ];
    }

    public static function getSystem(): array
    {
        return [
            'system.server.cpu' => \sys_getloadavg(),
            'system.server.memory.peak' => \memory_get_peak_usage(true),
            'system.server.memory.usage' => \memory_get_usage(true),
            'system.server.storage' => \disk_total_space(base_path()),
        ];
    }
}
