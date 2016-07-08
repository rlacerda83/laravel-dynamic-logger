<?php

namespace DynamicLogger;

use Illuminate\Support\ServiceProvider;

class DynamicLoggerServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('dynamic.logger', function ($app) {
            return new DynamicLogger();
        });

        $this->app->alias('dynamic.logger', 'DynamicLogger\DynamicLogger');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['DynamicLogger\DynamicLogger'];
    }
}
