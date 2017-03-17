<?php

namespace RummyKhan\MoloquentLogger\Logger;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use MongoDB\BSON\UTCDateTime;
use RummyKhan\MoloquentLogger\Model\MoloquentLog;

trait MoloquentLogger
{
    /**
     * @return mixed
     */
    public function logs()
    {
        return $this->morphMany(MoloquentLog::class, 'moloquent');
    }

    /**
     * Attach events while the Model is booting Models
     */
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

    /**
     * Log the Model changes when the Model is being created.
     *
     * @param string $action
     */
    protected function moloquentCreated($action = 'create')
    {
        if ($this->shouldLog()) {
            $this->logs()->save(new MoloquentLog([
                'after' => $this->getMoloquentChangedAttributes(),
                'after_model' => $this->toArray(),
                'scope' => $this->getMoloquentPublicScope('function', 'create'),
                'user_id' => Auth::id() ?: null,
                'action' => $action,
                'request' => Request::all()
            ]));
        }
    }

    /**
     * Log the Model changes when the model is being updated.
     * @param string $action
     */
    protected function moloquentUpdated($action = 'update')
    {
        if ($this->shouldLog()) {
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
    }

    /**
     * Log the Model when the model is being deleted.
     * @param string $action
     */
    protected function moloquentDeleted($action = 'delete')
    {
        if ($this->shouldLog()) {
            $this->logs()->save(new MoloquentLog([
                'before' => $this->getMoloquentDifferenceFromChanged(),
                'before_model' => $this->getMoloquentFresh(),
                'scope' => $this->getMoloquentPublicScope('function', 'delete'),
                'user_id' => Auth::id() ?: null,
                'action' => $action,
                'request' => Request::all()
            ]));
        }
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
     * check if current app environments not exist in ignore_environments in moloquent-logger config.
     */
    protected function shouldLog()
    {
        return !in_array(env('APP_ENV'), config('moloquent-logger.ignore_environments'));
    }


    /**
     * Get State of the model after a specific date.
     *
     * @param string $date
     * @return Collection
     */
    public function stateAfter($date)
    {
        return $this->logs()->where('created_at', '>=', $this->toUTCTime($date))->get();
    }

    /**
     * Get State of the model before specific date.
     *
     * @param string $date
     * @return Collection
     */
    public function stateBefore($date)
    {
        return $this->logs()->where('created_at', '<=', $this->toUTCTime($date))->get();
    }

    /**
     * Get State of model using a customized where.
     * By Default it will give you today State
     *
     * @param string $key
     * @param mixed $value
     *
     * @return Collection
     */
    public function stateAt($key = 'created_at', $value = null)
    {
        if (!$value) {
            $value = $this->toUTCTime(date('Y-m-d', time()));
        }
        return $this->logs()->where($key, $value)->get();
    }

    /**
     * @param $string_date
     * @return UTCDateTime
     */
    private function toUTCTime($string_date)
    {
        return new UTCDateTime(strtotime($string_date) * 1000);
    }
}