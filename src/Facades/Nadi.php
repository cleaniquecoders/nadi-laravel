<?php

namespace CleaniqueCoders\Nadi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CleaniqueCoders\Nadi\Nadi
 */
class Nadi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CleaniqueCoders\Nadi\Nadi::class;
    }
}
