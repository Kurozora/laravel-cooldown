<?php

namespace Kurozora\Cooldown\Models;

use Illuminate\Database\Eloquent\Model;

class Cooldown extends Model
{
    protected $table = 'kurozora_cooldowns';
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
    ];

    /**
     * Returns the model associated with the cooldown.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }
}