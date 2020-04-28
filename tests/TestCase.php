<?php

namespace Kurozora\Cooldown\Tests;

use Kurozora\Cooldown\CooldownServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load test migrations
        $this->loadMigrationsFrom(__DIR__ . '/Support/Migrations');
    }

    /**
     * Register the provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CooldownServiceProvider::class
        ];
    }

    /**
     * Set up the test environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Enable debug mode
        $app['config']->set('app.debug', true);

        // Configure database
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Configure cache
        $app['config']->set('cache.default', 'testing');
        $app['config']->set('cache.stores.testing', [
            'driver' => 'array'
        ]);
    }
}