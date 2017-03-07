<?php

namespace RummyKhan\MoloquentLogger\Logger;

use Jenssegers\Mongodb\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'before_attributes', 'after_attributes', 'before_model', 'after_model',
        'scope', 'action', 'request'
    ];

    protected $attributes = [
        'request' => null
    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Get all of the owning moloquent models.
     */
    public function loggable()
    {
        return $this->morphTo();
    }
}