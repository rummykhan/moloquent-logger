<?php

namespace RummyKhan\MoloquentLogger;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MoloquentLoggerServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

}