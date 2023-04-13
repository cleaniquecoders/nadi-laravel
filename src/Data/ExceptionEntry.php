<?php

namespace CleaniqueCoders\NadiLaravel\Data;

use Illuminate\Contracts\Debug\ExceptionHandler;

class ExceptionEntry extends Entry
{
    /**
     * The underlying exception instance.
     *
     * @var \Throwable
     */
    public $exception;

    /**
     * Create a new incoming entry instance.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function __construct($exception, $type, array $content)
    {
        $this->exception = $exception;

        parent::__construct($content, $type);
    }

    /**
     * Determine if the incoming entry is a reportable exception.
     *
     * @return bool
     */
    public function isReportableException()
    {
        $handler = app(ExceptionHandler::class);

        return method_exists($handler, 'shouldReport')
                ? $handler->shouldReport($this->exception) : true;
    }

    /**
     * Determine if the incoming entry is an exception.
     *
     * @return bool
     */
    public function isException()
    {
        return true;
    }

    /**
     * Calculate the family look-up hash for the incoming entry.
     *
     * @return string
     */
    public function familyHash()
    {
        return md5($this->content['file'].$this->content['line']);
    }
}
