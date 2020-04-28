<?php

namespace Kurozora\Cooldown;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Kurozora\Cooldown\Models\Cooldown;
use \Exception;

class CooldownServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        $this->cleanUpExpiredCooldowns();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        require 'helpers.php';
    }

    /**
     * Deletes all expired cooldowns. Only runs once per day.
     */
    public function cleanUpExpiredCooldowns()
    {
        // Get the datetime at which the cooldowns were last cleaned up
        /** @var Carbon $lastDeletedAt */
        $lastDeletedAt = Cache::get('kurozora_cooldown_last_cleaned_up_at', null);
        $hoursAgo = $lastDeletedAt == null ? null : $lastDeletedAt->diffInHours(now());

        // Delete the cooldowns if the last run was 24 hours ago or more
        if($hoursAgo >= 24 || $hoursAgo == null) {
            try {
                Cooldown::expired()->delete();
            }
            catch(Exception $e) { }

            Cache::forever('kurozora_cooldown_last_cleaned_up_at', now());
        }
    }
}