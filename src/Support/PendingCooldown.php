<?php

namespace Kurozora\Cooldown\Support;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Kurozora\Cooldown\Models\Cooldown;

class PendingCooldown
{
    /** @var string $name */
    private $name;

    /** @var Model|null $model */
    private $model;

    /**
     * @param string $name
     * @param Model|null $model
     */
    public function __construct($name, $model = null)
    {
        $this->name = $name;
        $this->model = $model;

        return $this;
    }

    /**
     * Sets the cooldown for the given time period string.
     * e.g.: '5 minutes', '10 days' etc.
     *
     * @param string $time
     * @return Cooldown
     */
    public function for($time)
    {
        $date = now()->add($time);

        return $this->createOrUpdateCooldownWithDate($date);
    }

    /**
     * Sets the cooldown to the given date.
     *
     * @param Carbon $date
     * @return Cooldown
     */
    public function until($date)
    {
        return $this->createOrUpdateCooldownWithDate($date);
    }

    /**
     * Deletes the cooldown instance to reset it.
     */
    public function reset()
    {
        $cooldown = $this->findCooldownWithName($this->name);

        if(!$cooldown) return;

        try {
            $cooldown->delete();
        }
        catch(Exception $e) { }
    }

    /**
     * Checks whether the cooldown period has passed.
     *
     * @return bool
     */
    public function passed()
    {
        $cooldown = $this->findCooldownWithName($this->name);

        if(!$cooldown)
            return true;

        if(now() < $cooldown->expires_at)
            return false;

        try {
            $cooldown->delete();
        }
        catch(Exception $e) { }

        return true;
    }

    /**
     * Returns the datetime at which the cooldown expires.
     *
     * @return Carbon|null
     */
    public function expiresAt()
    {
        if($this->passed()) return null;

        $cooldown = $this->findCooldownWithName($this->name);

        return $cooldown->expires_at;
    }

    /**
     * Returns the underlying Cooldown model, if any.
     *
     * @return Cooldown|null
     */
    public function get()
    {
        return $this->findCooldownWithName($this->name);
    }

    /**
     * Returns the first cooldown instance with the given name.
     *
     * @param string $name
     * @return null|Cooldown
     */
    private function findCooldownWithName($name)
    {
        return Cooldown::where('name', $name)
            ->where('model_type', $this->modelType())
            ->where('model_id', $this->modelId())
            ->first();
    }

    /**
     * Creates a cooldown instance with the given date.
     *
     * @param Carbon $date
     * @return Cooldown
     */
    private function createOrUpdateCooldownWithDate($date)
    {
        // Update existing cooldown
        if($cooldown = $this->findCooldownWithName($this->name))
        {
            $cooldown->expires_at = $date;
            $cooldown->save();

            return $cooldown;
        }

        // Create a new cooldown
        return Cooldown::create([
            'name'          => $this->name,
            'expires_at'    => $date,
            'model_type'    => $this->modelType(),
            'model_id'      => $this->modelId()
        ]);
    }

    /**
     * Returns the model's type.
     *
     * @return string|null
     */
    private function modelType()
    {
        if($this->model === null) return null;

        return get_class($this->model);
    }

    /**
     * Returns the model's ID.
     *
     * @return int|null
     */
    private function modelId()
    {
        if($this->model === null) return null;

        return (int) $this->model->id;
    }
}