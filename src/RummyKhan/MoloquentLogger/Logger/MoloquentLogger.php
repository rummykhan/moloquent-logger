<?php

namespace RummyKhan\MoloquentLogger\Logger;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait MoloquentLogger
{
    public function logs() {
        return $this->morphMany(Log::class,'moloquent');
    }

    public static function boot()
    {
        static::created(function ($model) {
            $model->moloquentCreated();
        });

        static::updating(function ($model) {
            $model->moloquentUpdated();
        });

        static::deleting(function ($model) {
            $model->moloquentDeleted($model);
        });

    }

    protected function moloquentCreated()
    {

    }

    protected function moloquentUpdated($action='update')
    {
        $this->logs()->save(new Log([
            'before' => json_encode($this->getMoloquentDifferenceFromChanged()),
            'after' => json_encode($this->getMoloquentChangedAttributes()),
            'model_before' => json_encode($this->getMoloquentFresh()),
            'model_after' => json_encode($this->toArray()),
            'scope' => $this->getMoloquentPublicScope('function', 'save'),
            'user_id' => Auth::id() ?: null,
            'action' => $action,
            'request' => json_encode(Request::all())
        ]));
    }

    protected function moloquentDeleted()
    {

    }

    /**
     * Get changed attributes of model.
     *
     * @return array|null
     */
    protected function getMoloquentChangedAttributes()
    {
        return $this->getDirty();
    }

    /**
     * Get a fresh model from database
     *
     * @return array
     */
    protected function getMoloquentFresh()
    {
        return $this->fresh()->toArray();
    }

    /**
     * Get the difference between old model and changed attributes..
     *
     * @return array
     */
    protected function getMoloquentDifferenceFromChanged()
    {
        return array_intersect_key($this->getMoloquentFresh(), $this->getMoloquentChangedAttributes());
    }


    /**
     * Get the public code scope from backtrace.
     *
     * @param string $key
     * @param string $value
     * @return array
     */
    protected function getMoloquentPublicScope($key, $value)
    {
        return collect($this->getMoloquentBackTrace())->where($key, $value)->first() ?: null;
    }


    /**
     * Get Backtrace
     *
     * @param int $limit ( Optional )
     * @return array
     */
    protected function getMoloquentBackTrace($limit = null)
    {
        $backtrace = debug_backtrace();

        if ($limit) {
            return array_splice($backtrace, 0, $limit);
        }

        return $backtrace;
    }

    /**
     * Get Model Name
     *
     * @return string
     */
    protected function getMoloquentModel()
    {
        return get_class($this);
    }
}