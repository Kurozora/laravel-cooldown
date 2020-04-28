<?php

namespace Kurozora\Cooldown\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Kurozora\Cooldown\CooldownServiceProvider;
use Kurozora\Cooldown\Models\Cooldown;
use Kurozora\Cooldown\Tests\TestCase;

class AutomaticCleanUpTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function expired_cooldowns_are_automatically_cleaned_up()
    {
        cooldown('registration')->for('25 minutes');

        Carbon::setTestNow(now()->addDay());

        (new CooldownServiceProvider(app()))->cleanUpExpiredCooldowns();

        $this->assertEquals(0, Cooldown::count());
    }

    /** @test */
    public function valid_cooldowns_survive_the_clean_up()
    {
        cooldown('registration')->for('1 day 2 hours');

        Carbon::setTestNow(now()->addDay());

        (new CooldownServiceProvider(app()))->cleanUpExpiredCooldowns();

        $this->assertEquals(1, Cooldown::count());
    }
}