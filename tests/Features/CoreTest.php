<?php

namespace CleaniqueCoders\NadiLaravel\Tests\Features;

use CleaniqueCoders\NadiLaravel\Tests\TestCase;

class CoreTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // $this->artisan('nadi:install');
    }

    /**
     * A basic test example.
     */
    public function test_core(): void
    {
        $this->assertTrue(true);
    }
}
