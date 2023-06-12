<?php

namespace CleaniqueCoders\NadiLaravel\Handler;

use CleaniqueCoders\Nadi\Data\Type;
use CleaniqueCoders\NadiLaravel\Data\Entry;
use Illuminate\Console\Events\CommandFinished;

class HandleCommandEvent extends Base
{
    public function handle(CommandFinished $event)
    {
        if ($this->shouldIgnore($event)) {
            return;
        }

        $command = $event->command ?? $event->input->getArguments()['command'] ?? 'default';
        $exitCode = $event->exitCode;

        $this->send(Entry::make(
            Type::COMMAND, [
                'command' => $command,
                'exit_code' => $exitCode,
                'arguments' => $event->input->getArguments(),
                'options' => $event->input->getOptions(),
            ]
        )->setHashFamily(
            $this->hash($command.$exitCode.date('Y-m-d'))
        )->toArray());
    }

    /**
     * Determine if the event should be ignored.
     *
     * @param  mixed  $event
     * @return bool
     */
    private function shouldIgnore($event)
    {
        return in_array($event->command, [
            'schedule:run',
            'schedule:finish',
            'package:discover',
        ]);
    }
}
