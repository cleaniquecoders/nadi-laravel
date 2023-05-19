<?php

namespace CleaniqueCoders\NadiLaravel\Transporter;

use Illuminate\Support\Facades\Log as Logger;
use Illuminate\Support\Str;

class Log implements Contract
{
    public function send(array $data)
    {
        return $this->log('nadi.log', $data);
    }

    public function test()
    {
        return ! empty(config('logging.channels.nadi'));
    }

    public function verify()
    {
        $this->log('nadi.verify');

        $path = file_exists(storage_path('logs/nadi-'.date('Y-m-d').'.log'));
        $content = file_get_contents($path);

        return $path && Str::of($content)->contains('nadi.verify');
    }

    public function log($key, $data = null)
    {
        Logger::channel('nadi')->error($key, $data);
    }
}
