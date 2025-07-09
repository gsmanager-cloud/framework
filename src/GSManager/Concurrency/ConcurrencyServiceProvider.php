<?php

namespace GSManager\Concurrency;

use GSManager\Contracts\Support\DeferrableProvider;
use GSManager\Support\ServiceProvider;

class ConcurrencyServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ConcurrencyManager::class, function ($app) {
            return new ConcurrencyManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ConcurrencyManager::class,
        ];
    }
}
