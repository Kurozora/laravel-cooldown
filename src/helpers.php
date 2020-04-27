<?php

use Kurozora\Cooldown\Support\PendingCooldown;

if(!function_exists('cooldown'))
{
    /**
     * @param string $name
     * @return PendingCooldown
     */
    function cooldown($name)
    {
        return new PendingCooldown($name);
    }
}