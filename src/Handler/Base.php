<?php

namespace CleaniqueCoders\NadiLaravel\Handler;

use CleaniqueCoders\NadiLaravel\Transporter;

class Base
{
    private Transporter $transporter;

    public function __construct()
    {
        $this->transporter = app('nadi');
    }

    public function send(array $data)
    {
        $this->transporter->send($data);
    }
}
