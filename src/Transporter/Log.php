<?php

namespace CleaniqueCoders\NadiLaravel\Transporter;

use Illuminate\Support\Facades\Log as Logger;

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

        $path = storage_path('logs/nadi-'.date('Y-m-d').'.log');
        $content = file_get_contents($path);

        return file_exists($path) && str_contains($content, 'nadi.verify');
    }

    public function log($key, $data = [])
    {
        Logger::channel('nadi')->error($key, $data);
    }
}
