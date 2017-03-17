<?php

namespace RummyKhan\MoloquentLogger\Model;

use App\User;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class MoloquentLog
 * @package RummyKhan\MoloquentLogger\Model
 */
class MoloquentLog extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'before', 'after', 'before_model', 'after_model',
        'scope', 'action', 'request', 'user_id'
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'request' => null,
        'before' => null,
        'after' => null,
        'before_model' => null,
        'after_model' => null
    ];

    /**
     * MoloquentLog constructor.
     *
     * @param array $attributes
     */
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
}