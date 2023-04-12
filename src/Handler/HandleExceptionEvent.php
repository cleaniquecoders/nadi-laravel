<?php

namespace CleaniqueCoders\NadiLaravel\Handler;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Laravel\Telescope\IncomingExceptionEntry;
use Laravel\Telescope\ExceptionContext;
use Laravel\Telescope\ExtractTags;
use Throwable;

class HandleExceptionEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageLogged $event): void
    {
        if($this->shouldIgnore($event)) {
            return;
        }

        $exception = $event->context['exception'];

        $trace = collect($exception->getTrace())->map(function ($item) {
            return Arr::only($item, ['file', 'line']);
        })->toArray();

        $entry = IncomingExceptionEntry::make($exception, [
            'class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            'context' => transform(Arr::except($event->context, ['exception', 'telescope']), function ($context) {
                return ! empty($context) ? $context : null;
            }),
            'trace' => $trace,
            'line_preview' => ExceptionContext::get($exception),
        ])->tags($this->tags($event));

        app('nadi')->send($entry->toArray());
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
