<?php

namespace Kurozora\Cooldown;

use Kurozora\Cooldown\Support\PendingCooldown;

trait HasCooldowns
{
    /**
     * @param string $name
     * @return PendingCooldown
     */
    public function cooldown($name)
    {
        return new PendingCooldown($name, $this);
    }
}