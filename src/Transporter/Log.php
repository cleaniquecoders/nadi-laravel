<?php

namespace CleaniqueCoders\NadiLaravel\Transporter;

use Illuminate\Support\Facades\Log as Logger;

class Log implements Contract
{
    public function send(iterable $data)
    {
        return Logger::channel('daily')->error('nadi.harvester', ['exception' => $data]);
    }
}
