<?php

namespace Kurozora\Cooldown\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Kurozora\Cooldown\HasCooldowns;

class User extends Model
{
    use HasCooldowns;

    protected $guarded = [];
}