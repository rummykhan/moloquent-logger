<?php

namespace RummyKhan\MoloquentLogger\Model;

use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\Model;

class MoloquentLog extends Model
{
    protected $fillable = [
        'before', 'after', 'before_model', 'after_model',
        'scope', 'action', 'request', 'user_id'
    ];

    protected $attributes = [
        'request' => null,
        'before' => null,
        'after' => null,
        'before_model' => null,
        'after_model' => null
    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('moloquent_logger.connection') ?: env('DB_CONNECTION');
        $this->collection = config('moloquent_logger.collection') ?: 'moloquent_logs';
    }

    /**
     * Get all of the owning moloquent models.
     */
    public function loggable()
    {
        return $this->morphTo();
    }
}