<?php

namespace CleaniqueCoders\NadiLaravel\Handler;

use CleaniqueCoders\Nadi\Data\Type;
use CleaniqueCoders\NadiLaravel\Actions\ExceptionContext;
use CleaniqueCoders\NadiLaravel\Actions\ExtractTags;
use CleaniqueCoders\NadiLaravel\Data\ExceptionEntry;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Arr;
use Throwable;

class HandleExceptionEvent extends Base
{
    /**
     * Handle the event.
     */
    public function handle(MessageLogged $event): void
    {
        if ($this->shouldIgnore($event)) {
            return;
        }

        $exception = $event->context['exception'];

        $trace = collect($exception->getTrace())->map(function ($item) {
            return Arr::only($item, ['file', 'line']);
        })->toArray();

        $this->store(
            ExceptionEntry::make(
                $exception,
                Type::EXCEPTION,
                [
                    'class' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'context' => transform(Arr::except($event->context, ['exception', 'telescope']), function ($context) {
                        return ! empty($context) ? $context : null;
                    }),
                    'trace' => $trace,
                    'line_preview' => ExceptionContext::get($exception),
                ]
            )->setHashFamily(
                $this->hash(
                    get_class($exception).
                    $exception->getFile().
                    $exception->getLine().
                    $exception->getMessage().
                    date('Y-m-d'))
            )->tags($this->tags($event))->toArray()
        );
    }

    /**
     * Extract the tags for the given event.
     *
     * @param  \Illuminate\Log\Events\MessageLogged  $event
     * @return array
     */
    protected function tags($event)
    {
        return array_merge(ExtractTags::from($event->context['exception']),
            $event->context['telescope'] ?? []
        );
    }

    /**
     * Determine if the event should be ignored.
     *
     * @param  mixed  $event
     * @return bool
     */
    private function shouldIgnore($event)
    {
        return ! isset($event->context['exception']) ||
            ! $event->context['exception'] instanceof Throwable;
    }
}
