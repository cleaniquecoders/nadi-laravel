<?php

namespace CleaniqueCoders\NadiLaravel\Transporter;

interface Contract
{
    public function send(array $data);

    public function test();

    public function verify();
}
