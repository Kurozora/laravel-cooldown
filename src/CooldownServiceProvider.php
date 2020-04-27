<?php

namespace Kurozora\Cooldown;

use Illuminate\Support\ServiceProvider;

class CooldownServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        require 'helpers.php';
    }
}