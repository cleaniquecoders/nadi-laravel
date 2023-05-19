<?php

namespace CleaniqueCoders\NadiLaravel\Support;

class Arr extends \Illuminate\Support\Arr
{
    /**
     * Convert a flatten "dot" notation array into an expanded array.
     *
     * @param  iterable  $array
     * @return array
     */
    public static function undot($array)
    {
        $results = [];

        foreach ($array as $key => $value) {
            static::set($results, $key, $value);
        }

        return $results;
    }
}
