<?php

namespace CleaniqueCoders\NadiLaravel\Concerns;

use CleaniqueCoders\NadiLaravel\Metric\Application;
use CleaniqueCoders\NadiLaravel\Metric\Framework;
use CleaniqueCoders\NadiLaravel\Metric\Http;
use CleaniqueCoders\NadiLaravel\Metric\Network;

trait InteractsWithMetric
{
    public function registerMetrics()
    {
        if (method_exists($this, 'addMetric')) {
            $this->addMetric(new Http);
            $this->addMetric(new Framework);
            $this->addMetric(new Application);
            $this->addMetric(new Network);
        }
    }
}
