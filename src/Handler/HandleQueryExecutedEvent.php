<?php

namespace CleaniqueCoders\NadiLaravel\Handler;

use CleaniqueCoders\Nadi\Data\Type;
use CleaniqueCoders\NadiLaravel\Concerns\FetchesStackTrace;
use CleaniqueCoders\NadiLaravel\Data\Entry;
use Illuminate\Database\Events\QueryExecuted;

class HandleQueryExecutedEvent extends Base
{
    use FetchesStackTrace;

    /**
     * Handle the event.
     */
    public function handle(QueryExecuted $event): void
    {
        $time = $event->time;
        $slow = $time > config('nadi.query.slow-threshold');

        if (! $slow) {
            return;
        }

        if ($caller = $this->getCallerFromStackTrace()) {
            $this->send(
                Entry::make(
                    Type::QUERY, [
                        'connection' => $event->connectionName,
                        'bindings' => $event->bindings,
                        'sql' => $this->replaceBindings($event),
                        'time' => number_format($time, 2, '.', ''),
                        'slow' => true,
                        'file' => $caller['file'],
                        'line' => $caller['line'],
                    ])
                    ->withFamilyHash($this->familyHash($event))
                    ->tags($this->tags($event))
                    ->toArray()
            );
        }
    }

    /**
     * Extract the tags for the given event.
     *
     * @param  \Illuminate\Database\Events\QueryExecuted  $event
     * @return array
     */
    protected function tags($event)
    {
        return isset($this->options['slow']) && $event->time >= $this->options['slow'] ? ['slow'] : [];
    }

    /**
     * Calculate the family look-up hash for the query event.
     *
     * @param  \Illuminate\Database\Events\QueryExecuted  $event
     * @return string
     */
    public function familyHash($event)
    {
        return md5($event->sql);
    }

    /**
     * Format the given bindings to strings.
     *
     * @param  \Illuminate\Database\Events\QueryExecuted  $event
     * @return array
     */
    protected function formatBindings($event)
    {
        return $event->connection->prepareBindings($event->bindings);
    }

    /**
     * Replace the placeholders with the actual bindings.
     *
     * @param  \Illuminate\Database\Events\QueryExecuted  $event
     * @return string
     */
    public function replaceBindings($event)
    {
        $sql = $event->sql;

        foreach ($this->formatBindings($event) as $key => $binding) {
            $regex = is_numeric($key)
                ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
                : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";

            if ($binding === null) {
                $binding = 'null';
            } elseif (! is_int($binding) && ! is_float($binding)) {
                $binding = $this->quoteStringBinding($event, $binding);
            }

            $sql = preg_replace($regex, $binding, $sql, 1);
        }

        return $sql;
    }

    /**
     * Add quotes to string bindings.
     *
     * @param  \Illuminate\Database\Events\QueryExecuted  $event
     * @param  string  $binding
     * @return string
     */
    protected function quoteStringBinding($event, $binding)
    {
        try {
            return $event->connection->getPdo()->quote($binding);
        } catch (\PDOException $e) {
            throw_if('IM001' !== $e->getCode(), $e);
        }

        // Fallback when PDO::quote function is missing...
        $binding = \strtr($binding, [
            chr(26) => '\\Z',
            chr(8) => '\\b',
            '"' => '\"',
            "'" => "\'",
            '\\' => '\\\\',
        ]);

        return "'".$binding."'";
    }
}
