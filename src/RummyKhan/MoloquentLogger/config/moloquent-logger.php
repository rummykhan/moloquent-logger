<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Connection
    |--------------------------------------------------------------------------
    |
    | Here you may specify the connection which will be used for logging.
    | By Default it uses application connection.
    |
    */
    'connection' => env('DB_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | table
    |--------------------------------------------------------------------------
    |
    | Here you may specify the table/collection to store moloquent logs.
    | By Default it uses `moloquent_logs` table
    |
    */
    'table' => 'moloquent_logs',

    /*
    |--------------------------------------------------------------------------
    | Ignore Environments
    |--------------------------------------------------------------------------
    |
    | Here you may specify the ignore environments for the logger.
    | In these environments it will not log the model changes.
    |
    */
    'ignore_environments' => ['test'],

    /*
    |--------------------------------------------------------------------------
    | Ignore Events
    |--------------------------------------------------------------------------
    |
    | Here you may specify the ignore events for the models.
    | Supported events are creating, updating, saving, deleting,
    | these events will not be captured by the logger.
    |
    */
    'ignore_events' => [],

    /*
    |--------------------------------------------------------------------------
    | Log Request ?
    |--------------------------------------------------------------------------
    |
    | Here you may specify if you want to log the request that is updating the model as well.
    | By default it's false.
    |
    */
    'log_request' => false,

    /*
    |--------------------------------------------------------------------------
    | Log Scope ?
    |--------------------------------------------------------------------------
    |
    | Here you may specify if you want to log the scope Classes and functions that are updating the model as well.
    | By default it's true.
    |
    */
    'log_scope' => true,


];