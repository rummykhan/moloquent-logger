<?php

namespace RummyKhan\MoloquentLogger\Logger;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use RummyKhan\MoloquentLogger\Model\MoloquentLog;

trait MoloquentLogger
{
    public function logs() {
        return $this->morphMany(MoloquentLog::class,'moloquent');
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

    protected function moloquentCreated($action='create')
    {
        $this->logs()->save(new MoloquentLog([
            'after' => $this->getMoloquentChangedAttributes(),
            'after_model' => $this->toArray(),
            'scope' => $this->getMoloquentPublicScope('function', 'create'),
            'user_id' => Auth::id() ?: null,
            'action' => $action,
            'request' => Request::all()
        ]));
    }

    protected function moloquentUpdated($action='update')
    {
        $this->logs()->save(new MoloquentLog([
            'before' => $this->getMoloquentDifferenceFromChanged(),
            'after' => $this->getMoloquentChangedAttributes(),
            'before_model' => $this->getMoloquentFresh(),
            'after_model' => $this->toArray(),
            'scope' => $this->getMoloquentPublicScope('function', 'save'),
            'user_id' => Auth::id() ?: null,
            'action' => $action,
            'request' => Request::all()
        ]));
    }

    protected function moloquentDeleted($action='delete')
    {
        $this->logs()->save(new MoloquentLog([
            'before' => $this->getMoloquentDifferenceFromChanged(),
            'before_model' => $this->getMoloquentFresh(),
            'scope' => $this->getMoloquentPublicScope('function', 'delete'),
            'user_id' => Auth::id() ?: null,
            'action' => $action,
            'request' => Request::all()
        ]));
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
}