<?php

namespace CleaniqueCoders\NadiLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CleaniqueCoders\NadiLaravel\Nadi
 */
class Nadi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CleaniqueCoders\NadiLaravel\Nadi::class;
    }
}
