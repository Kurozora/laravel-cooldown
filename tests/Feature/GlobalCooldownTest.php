<?php

namespace Kurozora\Cooldown\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Kurozora\Cooldown\Models\Cooldown;
use Kurozora\Cooldown\Tests\TestCase;

class GlobalCooldownTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function cooldown_helper_exists()
    {
        $this->assertTrue(function_exists('cooldown'));
    }

    /** @test */
    public function cooldown_helper_can_register_cooldowns_with_for_method()
    {
        cooldown('registration')->for('1 hour');

        $this->assertEquals(1, Cooldown::count());
    }

    /** @test */
    public function cooldown_helper_can_register_cooldowns_with_until_method()
    {
        cooldown('registration')->until(now()->addHour());

        $this->assertEquals(1, Cooldown::count());
    }

    /** @test */
    public function cooldown_helper_can_check_if_cooldowns_passed()
    {
        Carbon::setTestNow(now());

        $this->assertTrue(cooldown('registration')->passed());

        Cooldown::create([
            'name'          => 'registration',
            'expires_at'    => now()->addHour()
        ]);

        $this->assertFalse(cooldown('registration')->passed());

        Carbon::setTestNow(now()->addMinutes(59));

        $this->assertFalse(cooldown('registration')->passed());

        Carbon::setTestNow(now()->addMinutes(1));

        $this->assertTrue(cooldown('registration')->passed());
    }

    /** @test */
    public function cooldowns_are_deleted_when_they_are_expired()
    {
        cooldown('registration')->for('1 hour');

        Carbon::setTestNow(now()->addHour());

        $this->assertTrue(cooldown('registration')->passed());
        $this->assertEquals(0, Cooldown::count());
    }

    /** @test */
    public function a_cooldown_expiration_date_can_be_retrieved()
    {
        Carbon::setTestNow(now());

        cooldown('registration')->for('1 hour');

        $this->assertInstanceOf(Carbon::class, cooldown('registration')->expiresAt());
    }

    /** @test */
    public function global_cooldowns_are_updated_if_they_already_exist()
    {
        cooldown('registration')->for('1 hour');

        $this->assertEquals(1, Cooldown::count());

        cooldown('registration')->for('24 hours');

        $this->assertEquals(1, Cooldown::count());
    }

    /** @test */
    public function a_global_cooldown_can_be_reset()
    {
        cooldown('registration')->for('1 hour');

        $this->assertEquals(1, Cooldown::count());

        cooldown('registration')->reset();

        $this->assertEquals(0, Cooldown::count());
    }
}