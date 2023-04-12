<?php

namespace CleaniqueCoders\NadiLaravel;

use CleaniqueCoders\NadiLaravel\Transporter\Contract;

class Transporter
{
    protected string $driver;
    protected Contract $transporter;

    public function __construct()
    {
        $this->driver = '\\CleaniqueCoders\\NadiLaravel\\Transporter\\'.ucfirst(config('nadi.driver'));

        if(! class_exists($this->driver)) {
            throw new \Exception("$this->driver did not exists");
        }

        if(! in_array(Contract::class, class_implements($this->driver)) ) {
            throw new \Exception("$this->driver did not implement the \CleaniqueCoders\LaravelClient\Transpoert\Contract class.");
        }

        $this->transporter = new $this->driver;
    }

    public static function make()
    {
        return new self();
    }

    public function send(iterable $data)
    {
        return $this->transporter->send($data);
    }
}
