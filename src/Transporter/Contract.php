<?php

namespace CleaniqueCoders\NadiLaravel\Transporter;

interface Contract
{
    public function send(iterable $data);
}
