<?php

namespace Kurozora\Cooldown\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Kurozora\Cooldown\Models\Cooldown;
use Kurozora\Cooldown\Tests\Support\User;
use Kurozora\Cooldown\Tests\TestCase;

class ModelCooldownTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User $user */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::create(['name' => 'Kurozora']);
    }

    /** @test */
    public function cooldown_function_exists_on_model()
    {
        $this->assertTrue(method_exists($this->user, 'cooldown'));
    }

    /** @test */
    public function a_cooldown_can_be_registered_with_for_method()
    {
        $this->user->cooldown('create-post')->for('10 minutes');

        $this->assertEquals(1, Cooldown::count());
    }

    /** @test */
    public function a_cooldown_can_be_registered_with_until_method()
    {
        $this->user->cooldown('create-post')->until(now()->addMinutes(10));

        $this->assertEquals(1, Cooldown::count());
    }

    /** @test */
    public function model_cooldowns_are_updated_if_they_already_exist()
    {
        $this->user->cooldown('create-post')->for('1 hour');

        $this->assertEquals(1, Cooldown::count());

        $this->user->cooldown('create-post')->for('24 hours');

        $this->assertEquals(1, Cooldown::count());
    }

    /** @test */
    public function a_model_cooldown_can_be_reset()
    {
        $this->user->cooldown('create-post')->for('1 hour');

        $this->assertEquals(1, Cooldown::count());

        $this->user->cooldown('create-post')->reset();

        $this->assertEquals(0, Cooldown::count());
    }
}