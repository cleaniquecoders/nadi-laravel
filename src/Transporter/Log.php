<?php

namespace CleaniqueCoders\NadiLaravel\Transporter;

use Illuminate\Support\Facades\Log as Logger;

class Log implements Contract
{
    public function send(array $data)
    {
        return Logger::channel('daily')->error('nadi.log', $data);
    }
}
