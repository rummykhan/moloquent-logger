<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Moloquent Logger Connection
    |--------------------------------------------------------------------------
    |
    | Here you may specify the connection which will be used for logging.
    | By Default it uses application connection.
    |
    */
    'connection' => env('DB_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Moloquent Logger table
    |--------------------------------------------------------------------------
    |
    | Here you may specify the table/collection to store moloquent logs.
    | By Default it uses `moloquent_logs` table
    |
    */
    'table' => 'moloquent_logs',

    /*
    |--------------------------------------------------------------------------
    | Moloquent Logger Ignore Environments
    |--------------------------------------------------------------------------
    |
    | Here you may specify the ignore environments for the logger.
    | In these environments it will not log the model changes.
    |
    */
    'ignore_environments' => ['test'],

    /*
    |--------------------------------------------------------------------------
    | Moloquent Logger Ignore Events
    |--------------------------------------------------------------------------
    |
    | Here you may specify the ignore events for the models.
    | Supported events are creating, updating, saving, deleting,
    | these events will not be captured by the logger.
    |
    */
    'ignore_events' => [],
];