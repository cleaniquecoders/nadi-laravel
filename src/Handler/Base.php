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

    public function store(array $data)
    {
        $this->transporter->store($data);
    }

    public function hash($value)
    {
        return sha1($value);
    }
}
